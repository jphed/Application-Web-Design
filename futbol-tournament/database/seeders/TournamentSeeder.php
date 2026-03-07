<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Player;
use App\Models\FootballMatch;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear equipos (mínimo 8)
        $teams = Team::factory(8)->create();
        
        $this->command->info('Se han creado 8 equipos.');

        // 2. Crear jugadores para cada equipo (15-22 por equipo)
        foreach ($teams as $team) {
            $playerCount = rand(15, 22);
            $usedJerseyNumbers = [];
            
            for ($i = 0; $i < $playerCount; $i++) {
                // Generar número de camiseta único para este equipo
                do {
                    $jerseyNumber = rand(1, 99);
                } while (in_array($jerseyNumber, $usedJerseyNumbers));
                
                $usedJerseyNumbers[] = $jerseyNumber;
                
                Player::factory()->create([
                    'team_id' => $team->id,
                    'jersey_number' => $jerseyNumber,
                ]);
            }
        }
        
        $totalPlayers = Player::count();
        $this->command->info("Se han creado {$totalPlayers} jugadores en total.");

        // 3. Crear partidos (mínimo 20)
        FootballMatch::factory(20)->create();
        
        $totalMatches = FootballMatch::count();
        $this->command->info("Se han creado {$totalMatches} partidos.");
    }
}
