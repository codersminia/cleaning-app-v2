<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Project;
use App\Models\Weekday;
use App\Models\ProposalAreaType;
use Illuminate\Support\Facades\DB;

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
        // Fetch all projects for the given proposal ID
        $projects = Project::where('proposal_id', $proposal->id)
                            ->with('serviceType') // Eager load the service type relationship
                            ->get();

        // Transform the collection to include the area names
        $transformedProjects = $projects->map(function ($project) {
            // area_ids is a JSON array like [30, 29, 35], so we decode it
            $areaIds = json_decode($project->area_ids);

            $areaNames = [];
            if (is_array($areaIds) && count($areaIds) > 0) {
                 // Fetch names from proposal_area_types where the id is in our array
                $areaNames = ProposalAreaType::whereIn('id', $areaIds)->pluck('name')->toArray();
            }

            return [
                'id' => $project->id,
                'is_recurring' => $project->is_recurring,
                'frequency_id' => $project->frequency_id,
                'per' => $project->per,
                // Get the name from the eager-loaded relationship
                'service_type_name' => $project->serviceType ? $project->serviceType->name : 'N/A',
                'area_names' => $areaNames,
            ];
        });

        return response()->json(['projects' => $transformedProjects]);
    }

}
