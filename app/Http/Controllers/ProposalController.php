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
use App\Models\ProposalRecipient;

class ProposalController extends Controller
{

    public function getProposalsByProspect($id)
    {
        $proposals = Proposal::where('prospect_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($proposals);
    }

    public function destroy($id)
    {
        $proposal = Proposal::findOrFail($id);
        
        $proposal->delete();

        return response()->json(['message' => 'Proposal deleted successfully']);
    }

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

            if ($validated['type'] == 'commercial') {
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

            $isConstruction = ($proposal->commercial_category == 'construction_cleaning') || 
                          ($proposal->residential_category == 'construction_cleaning');

            if ($isConstruction && $proposal->phase) {
                
                $serviceTypeIds = [];

                switch ($proposal->phase) {
                    case 'phase_1':
                        $serviceTypeIds[] = 4; // Phase 1 Rough Clean
                        break;
                    case 'phase_2':
                        $serviceTypeIds[] = 5; // Phase 2 Final Clean
                        break;
                    case 'phase_3':
                        $serviceTypeIds[] = 6; // Phase 3 Touch up Clean
                        break;
                    case 'all_in_one':
                        $serviceTypeIds = [4, 5, 6]; // Create ALL three
                        break;
                }

                // Loop through and create the projects
                foreach ($serviceTypeIds as $sId) {
                    Project::create([
                        'proposal_id' => $proposal->id,
                        'service_type_id' => $sId,
                        'area_ids' => json_encode([39]), // 👈 Hardcoded requirement: always [39]
                        'is_recurring' => 0,             // 👈 Hardcoded: always One-Time
                        'frequency_id' => 1,             // 👈 Hardcoded
                        'per' => 'once',                 // 👈 Hardcoded
                    ]);
                }
            }
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

        return response()->json([
            'projects' => $transformedProjects,
            'proposal_type' => $proposal->proposal_type,
            'category' => $proposal->proposal_type == 'commercial' 
                ? $proposal->commercial_category 
                : $proposal->residential_category
            ]);
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
    // public function finalizeAndSend(Request $request, $id)
    // {
    //     $proposal = Proposal::findOrFail($id);
        
    //     $request->validate([
    //         'email' => 'required|email',
    //         'content_data' => 'required|array' // The text from your Vue sidebar
    //     ]);

    //     // Generate a token if one doesn't exist
    //     if (!$proposal->url_token) {
    //         $proposal->url_token = Str::random(64);
    //     }

    //     $proposal->status = 'sent';
    //     $proposal->sent_to_email = $request->email;
    //     $proposal->sent_at = now();
    //     $proposal->content_data = json_encode($request->content_data); // Save the customized text
    //     $proposal->save();

    //     // Development fix — works instantly
    //     $link = (app()->environment('local') 
    //         ? "http://localhost:8000" 
    //         : url('')) . "/view-proposal?token=" . $proposal->url_token;
        
    //     Mail::to($request->email)->send(new ProposalSentMail($proposal, $link));

    //     return response()->json(['success' => true, 'message' => 'Proposal sent successfully!']);
    // }

    public function finalizeAndSend(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        
        $request->validate([
            'email' => 'required|string', // Changed to string to handle commas
            'content_data' => 'required|array'
        ]);

        // 1. Update Proposal Main Info
        if (!$proposal->url_token) $proposal->url_token = Str::random(64);
        $proposal->status = 'sent';
        $proposal->sent_at = now();
        $proposal->content_data = json_encode($request->content_data);
        $proposal->save();

        // 2. Handle Recipients (Split by comma)
        $emails = array_map('trim', explode(',', $request->email));

        foreach ($emails as $email) {
            // Create a unique tracking record for this specific email
            $recipient = ProposalRecipient::create([
                'proposal_id' => $proposal->id,
                'email' => $email,
                'unique_token' => Str::random(32) // Unique ID for this person
            ]);

            // 3. Generate Unique Link
            // We append ?rid={id} so we know WHO opened it
            $link = url("/view-proposal?token={$proposal->url_token}&rid={$recipient->unique_token}");
            
            // Send Email
            Mail::to($email)->send(new ProposalSentMail($proposal, $link));
        }

        return response()->json(['success' => true, 'message' => 'Proposal sent successfully!']);
    }

    // 2. Public View (No Auth)
    // public function getPublicProposal($token)
    // {
    //     $proposal = Proposal::where('url_token', $token)->with('calculations', 'prospect')->firstOrFail();
        
    //     // Decode the saved content data
    //     $proposal->content_data = json_decode($proposal->content_data, true);
        
    //     // Check if already signed
    //     $signature = DB::table('proposal_signatures')->where('proposal_id', $proposal->id)->first();

    //     return response()->json([
    //         'proposal' => $proposal,
    //         'signature' => $signature
    //     ]);
    // }
    public function getPublicProposal($token)
    {
        // 1. Fetch Proposal with ALL relationships needed for dynamic HTML
        $proposal = Proposal::where('url_token', $token)
            ->with([
                'prospect',
                'calculations',
                // Scope of Work Data
                'proposalAreas.areaType',
                'proposalAreas.areaTasks.task.defaultFrequency', 
                'proposalAreas.areaTasks.customFrequency'
            ])
            ->firstOrFail();
        
        // 2. Fetch Projects associated with this proposal
        $projects = Project::where('proposal_id', $proposal->id)
            ->with('serviceType', 'projectTasks.task')
            ->get();

        // 3. Decode content_data if string
        if (is_string($proposal->content_data)) {
            $proposal->content_data = json_decode($proposal->content_data, true);
        }
        
        // 4. Check Signature
        $signature = DB::table('proposal_signatures')->where('proposal_id', $proposal->id)->first();

        // 5. Determine Logic Flags (Same as admin)
        $is_janitorial = in_array($proposal->commercial_category, ['janitorial_projects', 'janitorial_cleaning']) || 
                         in_array($proposal->residential_category, ['cleaning_projects']);
                         
        $is_construction = $proposal->commercial_category == 'construction_cleaning' || 
                           $proposal->residential_category == 'construction_cleaning';

        return response()->json([
            'proposal' => $proposal,
            'projects' => $projects,
            'signature' => $signature,
            'is_janitorial' => $is_janitorial,
            'is_construction' => $is_construction
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

    public function getFinalizeData($id)
    {
        $proposal = Proposal::with([
            'prospect', 
            'calculations', 
            // Load Scope of Work (Step 1 Data)
            'proposalAreas.areaType',
            'proposalAreas.areaTasks.task.defaultFrequency', 
            'proposalAreas.areaTasks.customFrequency'
        ])->findOrFail($id);

        // Load Projects (Step 2 Data)
        $projects = Project::where('proposal_id', $id)
            ->with('serviceType', 'projectTasks.task') // Load tasks inside projects if needed
            ->get();

        return response()->json([
            'proposal' => $proposal,
            'projects' => $projects,
            // Helper flags for UI logic
            'is_janitorial' => in_array($proposal->commercial_category, ['janitorial_projects', 'janitorial_cleaning']) || 
                               in_array($proposal->residential_category, ['cleaning_projects']),
            'is_construction' => $proposal->commercial_category == 'construction_cleaning' || 
                                 $proposal->residential_category == 'construction_cleaning',
        ]);
    }

    // Helper to calculate project total (needs to match Vue logic roughly)
    // private function calculateProjectGrandTotal($project) {
    //     // Simple reconstruction of total for display
    //     $sub = ($project['staff'] ?? 0) * ($project['rateOfPay'] ?? 0) * ($project['hours'] ?? 0);
    //     // Add overhead/margin logic here if needed for server-side precision, 
    //     // OR just trust the frontend sent correct totals if you saved them explicitly.
    //     // For now, assuming you might want to save the calculated totals directly in step 3 to avoid re-math here.
    //     return $project['marginDollar'] ? ($sub + $project['marginDollar']) : $sub; 
    // }
    // 1. GET: Fetch data for the Tracking List Page

    public function getTrackingList() {
        $proposals = Proposal::with(['recipients', 'prospect']) // Assuming relationships exist
            ->whereIn('status', ['sent', 'accepted'])
            ->whereCreatedBy(auth()->id())
            ->orderBy('sent_at', 'desc')
            ->get()
            ->map(function($p) {
                // Logic to find first open date
                $firstOpen = $p->recipients->whereNotNull('opened_at')->sortBy('opened_at')->first();
                return [
                    'id' => $p->id,
                    'prospect_name' => $p->prospect ? $p->prospect->company_name : 'Unknown', // Adjust based on your Prospect model
                    'location' => 'Detroit, MI', // Fetch real location from prospect
                    'proposal_name' => $p->proposal_name,
                    'sent_at' => $p->sent_at,
                    'opened_at' => $firstOpen ? $firstOpen->opened_at : null,
                    'status' => $p->signed_at ? 'signed' : 'sent',
                    'signed_by_email' => $p->signed_at ? $p->sent_to_email : null // Simplification
                ];
            });
        return response()->json($proposals);
    }

    // 2. GET: Fetch Recipients for a specific proposal
    public function getTrackingDetails($id) {
        $proposal = Proposal::with('recipients')->findOrFail($id);
        return response()->json([
            'proposal' => $proposal,
            'recipients' => $proposal->recipients
        ]);
    }

    // 3. GET: Fetch Time Logs for a recipient
    public function getRecipientActivity($recipientToken) {
        $recipient = ProposalRecipient::where('unique_token', $recipientToken)->firstOrFail();
        $logs = DB::table('proposal_tracking_logs')
                ->where('recipient_id', $recipient->id)
                ->get();
        
        return response()->json($logs);
    }

    // 4. POST: Record Time (Called from Client View)
    public function trackActivity(Request $request) {
        $recipient = ProposalRecipient::where('unique_token', $request->rid)->first();
        
        if($recipient) {
            // Mark as opened if not already
            if(!$recipient->opened_at) {
                $recipient->update(['opened_at' => now()]);
            }

            // Save time log
            DB::table('proposal_tracking_logs')->updateOrInsert(
                [
                    'proposal_id' => $recipient->proposal_id,
                    'recipient_id' => $recipient->id,
                    'section_name' => $request->section
                ],
                [
                    'duration_seconds' => DB::raw("duration_seconds + " . intval($request->seconds))
                ]
            );
        }
    }

}
