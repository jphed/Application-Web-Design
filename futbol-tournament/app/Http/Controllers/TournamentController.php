<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Player;
use App\Models\FootballMatch;

class TournamentController extends Controller
{
    // ==================== TEAMS ====================

    /**
     * Obtener todos los equipos
     */
    public function indexTeams()
    {
        $teams = Team::with('players')->get();
        return response()->json($teams);
    }

    /**
     * Obtener un equipo específico
     */
    public function showTeam($id)
    {
        $team = Team::with(['players', 'homeMatches', 'awayMatches'])->find($id);
        
        if (!$team) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }
        
        return response()->json($team);
    }

    /**
     * Crear un nuevo equipo
     */
    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'city' => 'required|string|max:255',
            'founded_year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        $team = Team::create($request->all());
        return response()->json($team, 201);
    }

    /**
     * Actualizar un equipo
     */
    public function updateTeam(Request $request, $id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $id,
            'city' => 'required|string|max:255',
            'founded_year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        $team->update($request->all());
        return response()->json($team);
    }

    /**
     * Eliminar un equipo
     */
    public function destroyTeam($id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }

        $team->delete();
        return response()->json(['message' => 'Equipo eliminado correctamente']);
    }

    // ==================== PLAYERS ====================

    /**
     * Obtener todos los jugadores
     */
    public function indexPlayers()
    {
        $players = Player::with('team')->get();
        return response()->json($players);
    }

    /**
     * Obtener jugadores de un equipo específico
     */
    public function getPlayersByTeam($teamId)
    {
        $team = Team::find($teamId);
        
        if (!$team) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }

        $players = $team->players;
        return response()->json($players);
    }

    /**
     * Obtener un jugador específico
     */
    public function showPlayer($id)
    {
        $player = Player::with('team')->find($id);
        
        if (!$player) {
            return response()->json(['error' => 'Jugador no encontrado'], 404);
        }
        
        return response()->json($player);
    }

    /**
     * Crear un nuevo jugador
     */
    public function storePlayer(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'position' => 'required|in:Goalkeeper,Defender,Midfielder,Forward',
            'jersey_number' => 'required|integer|min:1|max:99',
            'age' => 'required|integer|min:16|max:50',
        ]);

        // Verificar que el número de camiseta no esté en uso en el mismo equipo
        $existingPlayer = Player::where('team_id', $request->team_id)
                                ->where('jersey_number', $request->jersey_number)
                                ->first();

        if ($existingPlayer) {
            return response()->json(['error' => 'El número de camiseta ya está en uso en este equipo'], 422);
        }

        $player = Player::create($request->all());
        return response()->json($player, 201);
    }

    /**
     * Actualizar un jugador
     */
    public function updatePlayer(Request $request, $id)
    {
        $player = Player::find($id);
        
        if (!$player) {
            return response()->json(['error' => 'Jugador no encontrado'], 404);
        }

        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'position' => 'required|in:Goalkeeper,Defender,Midfielder,Forward',
            'jersey_number' => 'required|integer|min:1|max:99',
            'age' => 'required|integer|min:16|max:50',
        ]);

        // Verificar que el número de camiseta no esté en uso en el mismo equipo (excluyendo el jugador actual)
        $existingPlayer = Player::where('team_id', $request->team_id)
                                ->where('jersey_number', $request->jersey_number)
                                ->where('id', '!=', $id)
                                ->first();

        if ($existingPlayer) {
            return response()->json(['error' => 'El número de camiseta ya está en uso en este equipo'], 422);
        }

        $player->update($request->all());
        return response()->json($player);
    }

    /**
     * Eliminar un jugador
     */
    public function destroyPlayer($id)
    {
        $player = Player::find($id);
        
        if (!$player) {
            return response()->json(['error' => 'Jugador no encontrado'], 404);
        }

        $player->delete();
        return response()->json(['message' => 'Jugador eliminado correctamente']);
    }

    // ==================== MATCHES ====================

    /**
     * Obtener todos los partidos
     */
    public function indexMatches()
    {
        $matches = FootballMatch::with(['homeTeam', 'awayTeam'])
                               ->orderBy('match_date', 'desc')
                               ->get();
        return response()->json($matches);
    }

    /**
     * Obtener un partido específico
     */
    public function showMatch($id)
    {
        $match = FootballMatch::with(['homeTeam', 'awayTeam'])->find($id);
        
        if (!$match) {
            return response()->json(['error' => 'Partido no encontrado'], 404);
        }
        
        return response()->json($match);
    }

    /**
     * Crear un nuevo partido
     */
    public function storeMatch(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'match_date' => 'required|date|after_or_equal:today',
        ]);

        $match = FootballMatch::create($request->all());
        return response()->json($match, 201);
    }

    /**
     * Actualizar un partido
     */
    public function updateMatch(Request $request, $id)
    {
        $match = FootballMatch::find($id);
        
        if (!$match) {
            return response()->json(['error' => 'Partido no encontrado'], 404);
        }

        $request->validate([
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'match_date' => 'required|date',
        ]);

        $match->update($request->all());
        return response()->json($match);
    }

    /**
     * Eliminar un partido
     */
    public function destroyMatch($id)
    {
        $match = FootballMatch::find($id);
        
        if (!$match) {
            return response()->json(['error' => 'Partido no encontrado'], 404);
        }

        $match->delete();
        return response()->json(['message' => 'Partido eliminado correctamente']);
    }

    // ==================== STATISTICS ====================

    /**
     * Obtener estadísticas generales del torneo
     */
    public function getStatistics()
    {
        $stats = [
            'total_teams' => Team::count(),
            'total_players' => Player::count(),
            'total_matches' => FootballMatch::count(),
            'teams_with_most_players' => Team::withCount('players')
                                            ->orderBy('players_count', 'desc')
                                            ->take(3)
                                            ->get(),
            'recent_matches' => FootballMatch::with(['homeTeam', 'awayTeam'])
                                           ->orderBy('match_date', 'desc')
                                           ->take(5)
                                           ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Obtener estadísticas de un equipo específico
     */
    public function getTeamStatistics($id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }

        $homeMatches = $team->homeMatches;
        $awayMatches = $team->awayMatches;
        $allMatches = $homeMatches->concat($awayMatches);

        $wins = $allMatches->filter(function ($match) use ($id) {
            return ($match->home_team_id == $id && $match->home_score > $match->away_score) ||
                   ($match->away_team_id == $id && $match->away_score > $match->home_score);
        })->count();

        $losses = $allMatches->filter(function ($match) use ($id) {
            return ($match->home_team_id == $id && $match->home_score < $match->away_score) ||
                   ($match->away_team_id == $id && $match->away_score < $match->home_score);
        })->count();

        $draws = $allMatches->filter(function ($match) {
            return $match->home_score == $match->away_score;
        })->count();

        $goalsFor = $allMatches->sum(function ($match) use ($id) {
            return $match->home_team_id == $id ? $match->home_score : $match->away_score;
        });

        $goalsAgainst = $allMatches->sum(function ($match) use ($id) {
            return $match->home_team_id == $id ? $match->away_score : $match->home_score;
        });

        $stats = [
            'team' => $team,
            'total_matches' => $allMatches->count(),
            'wins' => $wins,
            'losses' => $losses,
            'draws' => $draws,
            'goals_for' => $goalsFor,
            'goals_against' => $goalsAgainst,
            'goal_difference' => $goalsFor - $goalsAgainst,
            'points' => ($wins * 3) + ($draws * 1),
            'players_count' => $team->players->count(),
        ];

        return response()->json($stats);
    }
}
