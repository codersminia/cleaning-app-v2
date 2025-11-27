<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalAreaType;
use App\Models\Task;
use App\Models\Frequency;
use App\Models\ProposalArea;
use App\Models\AreaTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalTasksController extends Controller
{
    /**
     * Fetch all necessary data for the ProposalTasks Vue component.
     */
    public function getDataForTasks(Proposal $proposal)
    {
        // Get prospect data
        $prospect = $proposal->prospect; // Assuming proposal has a 'prospect' relationship

        // Get all available area types
        $allAreaTypes = ProposalAreaType::all();

        // Get all available tasks with their default frequencies
        $allTasks = Task::with('defaultFrequency')->get();

        // Get all available frequencies
        $allFrequencies = Frequency::all();

        // Load existing proposal areas and their tasks for this proposal
        $existingProposalAreas = ProposalArea::with([
            'areaType',
            'areaTasks' => function ($query) {
                $query->with('task.defaultFrequency', 'customFrequency');
            }
        ])
        ->where('proposal_id', $proposal->id)
        ->get();

        return response()->json([
            'proposal' => $proposal,
            'prospect' => $prospect,
            'allAreaTypes' => $allAreaTypes,
            'allTasks' => $allTasks,
            'allFrequencies' => $allFrequencies,
            'existingProposalAreas' => $existingProposalAreas,
        ]);
    }

    /**
     * Store a new proposal area (when an area type is selected).
     */
    public function storeArea(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'area_type_id' => 'required|exists:proposal_area_types,id',
            'rooms' => 'nullable|integer|min:1',
            'hardfloor' => 'nullable|boolean',
            'carpet' => 'nullable|boolean',
            'hours' => 'nullable|integer|min:0',
            'minutes' => 'nullable|integer|min:0',
            'sqft' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            // Check if the area already exists for this proposal
            $proposalArea = $proposal->proposalAreas()->firstOrCreate(
                ['area_type_id' => $validated['area_type_id']],
                [
                    'rooms' => $validated['rooms'] ?? 1,
                    'hardfloor' => $validated['hardfloor'] ?? false,
                    'carpet' => $validated['carpet'] ?? false,
                    'hours' => $validated['hours'] ?? 0,
                    'minutes' => $validated['minutes'] ?? 0,
                    'sqft' => $validated['sqft'] ?? 0,
                    'notes' => $validated['notes'] ?? null,
                ]
            );

            // If it was just created, load relations for the response
            if ($proposalArea->wasRecentlyCreated) {
                $proposalArea->load('areaType', 'areaTasks');
            }

            return response()->json(['success' => true, 'proposalArea' => $proposalArea], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to store proposal area: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing proposal area's details.
     */
    public function updateArea(Request $request, Proposal $proposal, ProposalArea $proposalArea)
    {
        // Ensure the proposalArea belongs to the current proposal
        if ($proposalArea->proposal_id !== $proposal->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $validated = $request->validate([
            'rooms' => 'nullable|integer|min:1',
            'hardfloor' => 'nullable|boolean',
            'carpet' => 'nullable|boolean',
            'hours' => 'nullable|integer|min:0',
            'minutes' => 'nullable|integer|min:0',
            'sqft' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $proposalArea->update($validated);
            $proposalArea->load('areaType', 'areaTasks.task.defaultFrequency', 'areaTasks.customFrequency'); // Reload for response

            return response()->json(['success' => true, 'proposalArea' => $proposalArea]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update proposal area: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a proposal area (when an area type is deselected).
     */
    public function deleteArea(Proposal $proposal, ProposalArea $proposalArea)
    {
        // Ensure the proposalArea belongs to the current proposal
        if ($proposalArea->proposal_id !== $proposal->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        try {
            $proposalArea->delete(); // This will also cascade delete associated AreaTasks
            return response()->json(['success' => true, 'message' => 'Proposal area deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete proposal area: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a new task for a proposal area.
     */
    public function storeAreaTask(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'proposal_area_id' => 'required|exists:proposal_areas,id',
            'task_id' => 'required|exists:tasks,id',
            'custom_description' => 'nullable|string',
            'custom_frequency_id' => 'nullable|exists:frequencies,id',
        ]);

        // Ensure the proposal_area_id belongs to the current proposal
        $proposalArea = ProposalArea::findOrFail($validated['proposal_area_id']);
        if ($proposalArea->proposal_id !== $proposal->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        try {
            $areaTask = AreaTask::firstOrCreate(
                ['proposal_area_id' => $validated['proposal_area_id'], 'task_id' => $validated['task_id']],
                [
                    'custom_description' => $validated['custom_description'] ?? null,
                    'custom_frequency_id' => $validated['custom_frequency_id'] ?? null,
                ]
            );

            $areaTask->load('task.defaultFrequency', 'customFrequency'); // Reload for response

            return response()->json(['success' => true, 'areaTask' => $areaTask], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to store area task: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing area task (e.g., custom frequency or description).
     */
    public function updateAreaTask(Request $request, Proposal $proposal, AreaTask $areaTask)
    {
        // Ensure the areaTask's proposal_area belongs to the current proposal
        if ($areaTask->proposalArea->proposal_id !== $proposal->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $validated = $request->validate([
            'custom_description' => 'nullable|string',
            'custom_frequency_id' => 'nullable|exists:frequencies,id',
        ]);

        try {
            $areaTask->update($validated);
            $areaTask->load('task.defaultFrequency', 'customFrequency'); // Reload for response

            return response()->json(['success' => true, 'areaTask' => $areaTask]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update area task: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete an area task (when a task is deselected).
     */
    public function deleteAreaTask(Proposal $proposal, AreaTask $areaTask)
    {
        // Ensure the areaTask's proposal_area belongs to the current proposal
        if ($areaTask->proposalArea->proposal_id !== $proposal->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        try {
            $areaTask->delete();
            return response()->json(['success' => true, 'message' => 'Area task deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete area task: ' . $e->getMessage()], 500);
        }
    }
}