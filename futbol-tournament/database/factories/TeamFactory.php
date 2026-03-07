<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teamNames = [
            'Real Madrid', 'Barcelona', 'Manchester United', 'Liverpool', 
            'Bayern Munich', 'PSG', 'Juventus', 'Ajax', 'Porto', 'Celtic',
            'River Plate', 'Boca Juniors', 'Flamengo', 'Corinthians'
        ];

        $cities = [
            'Madrid', 'Barcelona', 'Manchester', 'Liverpool', 'Munich',
            'Paris', 'Turin', 'Amsterdam', 'Porto', 'Glasgow',
            'Buenos Aires', 'Rio de Janeiro', 'São Paulo'
        ];

        return [
            'name' => fake()->unique()->randomElement($teamNames),
            'city' => fake()->randomElement($cities),
            'founded_year' => fake()->numberBetween(1900, 2010),
        ];
    }
}
