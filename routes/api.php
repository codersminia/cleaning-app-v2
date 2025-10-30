<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;

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