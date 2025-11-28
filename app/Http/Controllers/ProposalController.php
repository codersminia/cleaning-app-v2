<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Project;
use App\Models\Weekday;
use App\Models\ProposalAreaType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProposalSentMail;
use App\Models\ProposalSignature;

class ProposalController extends Controller
{
    // ✅ Store a new proposal
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prospect_id' => 'required|integer',
            'proposal_name' => 'required|string|max:255',
            'type' => 'required|in:commercial,residential',
            'facility_id' => 'nullable|integer',
            'category' => 'required|string',
            'selectedDays' => 'array',
            'phase' => 'nullable|in:phase_1,phase_2,phase_3,all_in_one',
        ]);

        DB::beginTransaction();
        try {
            // Map category fields according to proposal type
            $proposalData = [
                'prospect_id' => $validated['prospect_id'],
                'proposal_name' => $validated['proposal_name'],
                'proposal_type' => $validated['type'],
                'facility_id' => $validated['facility_id'] ?? null,
                'phase' => $validated['phase'] ?? null,
                'status' => 'draft',
                'created_by' => auth()->id() ?? 1, // placeholder for demo
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($validated['type'] === 'commercial') {
                $proposalData['commercial_category'] = match ($validated['category']) {
                    'janitorial_projects' => 'janitorial_cleaning',
                    'construction_cleaning' => 'construction_cleaning',
                    'projects_only' => 'projects',
                    default => null,
                };
            } else {
                $proposalData['residential_category'] = match ($validated['category']) {
                    'cleaning_projects' => 'cleaning_projects',
                    'construction_cleaning' => 'construction_cleaning',
                    'projects_only' => 'projects_only',
                    default => null,
                };
            }

            // Create proposal
            $proposal = Proposal::create($proposalData);

            // Attach weekdays if any
            if (!empty($validated['selectedDays'])) {
                $proposal->weekdays()->attach($validated['selectedDays']);
            }

            DB::commit();
            return response()->json(['success' => true, 'proposal' => $proposal], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showTasks($id)
    {
        return view('ProposalTasks', ['proposalId' => $id]);
    }

    public function getProjectsForCalculator(Proposal $proposal)
    {
        $projects = Project::where('proposal_id', $proposal->id)
                            ->with('serviceType')
                            ->get();

        $transformedProjects = $projects->map(function ($project) {
            
            // SAFETY CHECK: Only json_decode if it's a string. 
            // If Laravel already cast it to an array, use it directly.
            $areaIds = is_string($project->area_ids) ? json_decode($project->area_ids, true) : $project->area_ids;

            $areaNames = [];
            if (is_array($areaIds) && count($areaIds) > 0) {
                $areaNames = ProposalAreaType::whereIn('id', $areaIds)->pluck('name')->toArray();
            }

            return [
                'id' => $project->id,
                'is_recurring' => $project->is_recurring,
                'frequency_id' => $project->frequency_id, 
                'per' => $project->per,
                'service_type_name' => $project->serviceType ? $project->serviceType->name : 'Project',
                'area_names' => $areaNames,
            ];
        });

        return response()->json(['projects' => $transformedProjects]);
    }

    public function saveCalculation(Request $request, $id)
    {
        // Validate that proposal exists
        $proposal = Proposal::findOrFail($id);

        // Upsert (Update if exists, Insert if not)
        DB::table('proposal_calculations')->updateOrInsert(
            ['proposal_id' => $id],
            [
                'calculator_data' => json_encode($request->all()),
                'updated_at' => now(),
                'created_at' => now() // Only used on insert, ignored on update usually
            ]
        );

        return response()->json(['message' => 'Saved successfully']);
    }

    public function getCalculation($id)
    {
        $calculation = DB::table('proposal_calculations')->where('proposal_id', $id)->first();

        if (!$calculation) {
            return response()->json([], 200); // Return empty if nothing saved yet
        }

        return response()->json(json_decode($calculation->calculator_data, true));
    }

    // 1. Save Content (Sidebar text), Generate Token, Send Email
    public function finalizeAndSend(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        
        $request->validate([
            'email' => 'required|email',
            'content_data' => 'required|array' // The text from your Vue sidebar
        ]);

        // Generate a token if one doesn't exist
        if (!$proposal->url_token) {
            $proposal->url_token = Str::random(64);
        }

        $proposal->status = 'sent';
        $proposal->sent_to_email = $request->email;
        $proposal->sent_at = now();
        $proposal->content_data = json_encode($request->content_data); // Save the customized text
        $proposal->save();

        // Development fix — works instantly
        $link = (app()->environment('local') 
            ? "http://localhost:8000" 
            : url('')) . "/view-proposal?token=" . $proposal->url_token;
        
        Mail::to($request->email)->send(new ProposalSentMail($proposal, $link));

        return response()->json(['success' => true, 'message' => 'Proposal sent successfully!']);
    }

    // 2. Public View (No Auth)
    public function getPublicProposal($token)
    {
        $proposal = Proposal::where('url_token', $token)->with('calculations', 'prospect')->firstOrFail();
        
        // Decode the saved content data
        $proposal->content_data = json_decode($proposal->content_data, true);
        
        // Check if already signed
        $signature = DB::table('proposal_signatures')->where('proposal_id', $proposal->id)->first();

        return response()->json([
            'proposal' => $proposal,
            'signature' => $signature
        ]);
    }

    // 3. Process Signature (No Auth)
    public function signProposal(Request $request, $token)
    {
        $proposal = Proposal::where('url_token', $token)->firstOrFail();

        $validated = $request->validate([
            'signer_name' => 'required|string',
            'signer_title' => 'required|string',
            'signature_image' => 'required|string', // Base64
        ]);

        DB::beginTransaction();
        try {
            // Save Signature
            ProposalSignature::create([
                'proposal_id' => $proposal->id,
                'signer_name' => $validated['signer_name'],
                'signer_title' => $validated['signer_title'],
                'signer_email' => $proposal->sent_to_email,
                'signature_image' => $validated['signature_image'],
                'ip_address' => $request->ip(),
                'signed_at' => now(),
            ]);

            // Update Proposal Status
            $proposal->status = 'accepted';
            $proposal->signed_at = now();
            $proposal->save();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
