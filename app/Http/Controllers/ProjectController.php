<?php

namespace App\Http\Controllers;

use App\Models\ServiceType; // Assuming you have a ServiceType model
use App\Models\ProposalAreaType; // Assuming you have a ProposalAreaType model
use App\Models\Project; // Assuming you have a Project model
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * Fetch data for the "Add New Project" modal.
     * Includes service types, proposal area types, frequencies, and 'per' options.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProjectModalData()
    {
        // Fetch service types
        $serviceTypes = ServiceType::select('id', 'name')->get()->map(function($type) {
            return ['value' => $type->id, 'text' => $type->name];
        });

        // Fetch proposal area types
        $proposalAreaTypes = ProposalAreaType::select('id', 'name')->get()->map(function($area) {
            return ['value' => $area->id, 'text' => $area->name];
        });

        // Frequencies (1 to 30)
        $frequencies = [];
        for ($i = 1; $i <= 30; $i++) {
            $frequencies[] = ['value' => $i, 'text' => (string)$i];
        }

        // 'Per' options
        $perOptions = [
            ['value' => 'week', 'text' => 'Week'],
            ['value' => 'month', 'text' => 'Month'],
            ['value' => 'quarter', 'text' => 'Quarter'],
            ['value' => 'year', 'text' => 'Year'],
        ];

        return response()->json([
            'serviceTypes' => $serviceTypes,
            'proposalAreaTypes' => $proposalAreaTypes,
            'frequencies' => $frequencies,
            'perOptions' => $perOptions,
        ]);
    }

    /**
     * Store a new project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeProject(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'proposal_id' => 'required|exists:proposals,id',
                'serviceType_id' => 'required|exists:service_types,id',
                'area_ids' => 'required|array|min:1',
                'area_ids.*' => 'exists:proposal_area_types,id', // Validate each ID in the array
                'is_recurring' => 'required|boolean',
                'frequency_id' => 'nullable|integer|min:1|max:30', // frequency_id should be frequency number (1-30)
                'per' => 'nullable|in:week,month,quarter,year,once',
            ]);

            // Handle 'one-time' project specific logic
            if (!$validatedData['is_recurring']) {
                $validatedData['frequency_id'] = 1; // Set to 1 for one-time projects
                $validatedData['per'] = 'once'; 
            } else {
                // Ensure frequency_id and per are present for recurring projects
                if (empty($validatedData['frequency_id']) || empty($validatedData['per'])) {
                    throw ValidationException::withMessages([
                        'frequency_id' => 'Frequency is required for recurring projects.',
                        'per' => 'Per is required for recurring projects.',
                    ]);
                }
            }
            $validatedData['area_ids'] = json_encode($validatedData['area_ids']);

            // Create project
            $project = Project::create([
                'proposal_id' => $validatedData['proposal_id'],
                'service_type_id' => $validatedData['serviceType_id'],
                'area_ids' => $validatedData['area_ids'], // Stored as JSON string
                'is_recurring' => $validatedData['is_recurring'],
                'frequency_id' => $validatedData['frequency_id'],
                'per' => $validatedData['per'],
            ]);


            return response()->json(['message' => 'Project created successfully!', 'project' => $project], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Error storing project: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while creating the project.'], 500);
        }
    }
}