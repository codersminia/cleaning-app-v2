<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TrackingController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/prospects', [DashboardController::class, 'prospects'])->name('prospects');
Route::get('/location/{id}', [DashboardController::class, 'showLocation'])->name('location.show');
Route::get('/proposals/{proposal}/{any?}', [ProposalController::class, 'showTasks'])
    ->where('any', '.*')
    ->name('proposals.tasks');
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

