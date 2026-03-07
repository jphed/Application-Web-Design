<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FootballMatch>
 */
class FootballMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teams = Team::pluck('id')->toArray();
        
        if (count($teams) < 2) {
            throw new \Exception('Se necesitan al menos 2 equipos para crear partidos');
        }

        $homeTeamId = fake()->randomElement($teams);
        $awayTeamId = fake()->randomElement(array_diff($teams, [$homeTeamId]));

        return [
            'home_team_id' => $homeTeamId,
            'away_team_id' => $awayTeamId,
            'home_score' => fake()->numberBetween(0, 5),
            'away_score' => fake()->numberBetween(0, 5),
            'match_date' => fake()->dateTimeBetween('-6 months', '+6 months'),
        ];
    }
}
