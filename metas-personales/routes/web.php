<?php

use App\Http\Controllers\Admin\GoalController as AdminGoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('goals', GoalController::class);

    Route::prefix('goals/{goal}')->name('goals.')->group(function () {
        Route::post('milestones', [MilestoneController::class, 'store'])->name('milestones.store');
        Route::patch('milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
        Route::patch('milestones/{milestone}/toggle', [MilestoneController::class, 'toggle'])->name('milestones.toggle');
        Route::delete('milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');

        Route::post('progress-logs', [ProgressLogController::class, 'store'])->name('progress-logs.store');
        Route::delete('progress-logs/{progressLog}', [ProgressLogController::class, 'destroy'])->name('progress-logs.destroy');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/goals', [AdminGoalController::class, 'index'])->name('goals.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
