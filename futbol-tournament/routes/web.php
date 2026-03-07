<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;

Route::get('/', function () {
    return view('welcome');
});

// ==================== TEAMS ====================
Route::get('/teams', [TournamentController::class, 'indexTeams']);
Route::get('/teams/{id}', [TournamentController::class, 'showTeam']);
Route::get('/teams/{id}/players', [TournamentController::class, 'getPlayersByTeam']);
Route::get('/teams/{id}/statistics', [TournamentController::class, 'getTeamStatistics']);

// ==================== PLAYERS ====================
Route::get('/players', [TournamentController::class, 'indexPlayers']);
Route::get('/players/{id}', [TournamentController::class, 'showPlayer']);

// ==================== MATCHES ====================
Route::get('/matches', [TournamentController::class, 'indexMatches']);
Route::get('/matches/{id}', [TournamentController::class, 'showMatch']);

// ==================== STATISTICS ====================
Route::get('/statistics', [TournamentController::class, 'getStatistics']);
