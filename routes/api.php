<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;   
use App\Http\Controllers\ProposalTasksController;   
use App\Http\Controllers\ProjectController;   

Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/saveprospect', [DashboardController::class, 'saveprospect']);
Route::get('/prospects', [DashboardController::class, 'getProspects']);
Route::get('/facilities', [DashboardController::class, 'getfacilities']);
Route::get('/frequencies', [DashboardController::class, 'getfrequencies']);
Route::get('/weekdays', [DashboardController::class, 'getweekdays']);
Route::post('/proposals', [ProposalController::class, 'store']);

Route::get('/project-modal-data', [ProjectController::class, 'getProjectModalData']);
Route::post('/projects', [ProjectController::class, 'storeProject']);
Route::get('/proposals/{proposalId}/projects-and-tasks', [ProjectController::class, 'getProjectsAndTasks']);
Route::post('/project-tasks/toggle', [ProjectController::class, 'toggleTask']);

Route::get('/proposals/{proposal}/projects-for-calculator', [ProposalController::class, 'getProjectsForCalculator']);

Route::prefix('proposals/{proposal}')->group(function () {
    Route::get('data-for-tasks', [ProposalTasksController::class, 'getDataForTasks']);
    Route::post('areas', [ProposalTasksController::class, 'storeArea']);
    Route::delete('areas/{proposalArea}', [ProposalTasksController::class, 'deleteArea']); // For deselecting an area
    Route::put('areas/{proposalArea}', [ProposalTasksController::class, 'updateArea']); // For updating area details (rooms, carpet, etc.)

    Route::post('area-tasks', [ProposalTasksController::class, 'storeAreaTask']);
    Route::delete('area-tasks/{areaTask}', [ProposalTasksController::class, 'deleteAreaTask']);
    Route::put('area-tasks/{areaTask}', [ProposalTasksController::class, 'updateAreaTask']); // For updating custom frequency/description
});