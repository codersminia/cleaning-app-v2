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

}
