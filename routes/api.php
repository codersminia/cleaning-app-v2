<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;   
use App\Http\Controllers\ProposalTasksController;   
use App\Http\Controllers\ProjectController;   

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);

// PUBLIC ROUTES (No Auth Middleware)
Route::get('proposal/{token}', [ProposalController::class, 'getPublicProposal']);
Route::post('proposal/{token}/sign', [ProposalController::class, 'signProposal']);

Route::middleware(['auth:sanctum'])->group(function () {

    // --- User Management ---
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- Dashboard & Prospects ---
    Route::post('/saveprospect', [DashboardController::class, 'saveprospect']);
    Route::get('/prospects', [DashboardController::class, 'getProspects']);
    Route::get('/facilities', [DashboardController::class, 'getfacilities']);
    Route::get('/frequencies', [DashboardController::class, 'getfrequencies']);
    Route::get('/weekdays', [DashboardController::class, 'getweekdays']);

    // --- Proposals ---
    Route::post('/proposals', [ProposalController::class, 'store']);
    Route::get('/proposals/{proposal}/projects-for-calculator', [ProposalController::class, 'getProjectsForCalculator']);
    
    // Calculator
    Route::get('/proposals/{id}/calculator', [ProposalController::class, 'getCalculation']);
    Route::post('/proposals/{id}/calculator', [ProposalController::class, 'saveCalculation']);

    // --- Projects ---
    Route::get('/project-modal-data', [ProjectController::class, 'getProjectModalData']);
    Route::post('/projects', [ProjectController::class, 'storeProject']);
    Route::get('/proposals/{proposalId}/projects-and-tasks', [ProjectController::class, 'getProjectsAndTasks']);
    Route::post('/project-tasks/toggle', [ProjectController::class, 'toggleTask']);

    Route::get('/proposals/{id}/finalize-data', [ProposalController::class, 'getFinalizeData']);

    // --- Proposal Tasks (Nested Group for Clarity) ---
    Route::prefix('proposals/{proposal}')->group(function () {
        Route::get('data-for-tasks', [ProposalTasksController::class, 'getDataForTasks']);
        
        // Areas
        Route::post('areas', [ProposalTasksController::class, 'storeArea']);
        Route::delete('areas/{proposalArea}', [ProposalTasksController::class, 'deleteArea']);
        Route::put('areas/{proposalArea}', [ProposalTasksController::class, 'updateArea']);

        // Tasks
        Route::post('area-tasks', [ProposalTasksController::class, 'storeAreaTask']);
        Route::delete('area-tasks/{areaTask}', [ProposalTasksController::class, 'deleteAreaTask']);
        Route::put('area-tasks/{areaTask}', [ProposalTasksController::class, 'updateAreaTask']);

        Route::post('finalize-and-send', [ProposalController::class, 'finalizeAndSend']);
    });

});