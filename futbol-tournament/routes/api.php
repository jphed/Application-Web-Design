<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    
    // ==================== TEAMS ====================
    Route::get('/teams', [TournamentController::class, 'indexTeams']);
    Route::get('/teams/{id}', [TournamentController::class, 'showTeam']);
    Route::get('/teams/{id}/players', [TournamentController::class, 'getPlayersByTeam']);
    Route::get('/teams/{id}/statistics', [TournamentController::class, 'getTeamStatistics']);
    Route::post('/teams', [TournamentController::class, 'storeTeam']);
    Route::put('/teams/{id}', [TournamentController::class, 'updateTeam']);
    Route::delete('/teams/{id}', [TournamentController::class, 'destroyTeam']);
    
    // ==================== PLAYERS ====================
    Route::get('/players', [TournamentController::class, 'indexPlayers']);
    Route::get('/players/{id}', [TournamentController::class, 'showPlayer']);
    Route::post('/players', [TournamentController::class, 'storePlayer']);
    Route::put('/players/{id}', [TournamentController::class, 'updatePlayer']);
    Route::delete('/players/{id}', [TournamentController::class, 'destroyPlayer']);
    
    // ==================== MATCHES ====================
    Route::get('/matches', [TournamentController::class, 'indexMatches']);
    Route::get('/matches/{id}', [TournamentController::class, 'showMatch']);
    Route::post('/matches', [TournamentController::class, 'storeMatch']);
    Route::put('/matches/{id}', [TournamentController::class, 'updateMatch']);
    Route::delete('/matches/{id}', [TournamentController::class, 'destroyMatch']);
    
    // ==================== STATISTICS ====================
    Route::get('/statistics', [TournamentController::class, 'getStatistics']);
});
