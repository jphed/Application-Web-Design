<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\ProgressLogController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/goals', [GoalController::class, 'index']);
    Route::post('/goals', [GoalController::class, 'store']);
    Route::get('/goals/{goal}', [GoalController::class, 'show']);
    Route::put('/goals/{goal}', [GoalController::class, 'update']);
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy']);

    Route::get('/goals/{goal}/logs', [ProgressLogController::class, 'index']);
    Route::post('/goals/{goal}/logs', [ProgressLogController::class, 'store']);
});
