<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'];
        $firstNames = ['John', 'Carlos', 'Luis', 'James', 'Robert', 'Marco', 'David', 'Paul'];
        $lastNames = ['Smith', 'Rodriguez', 'Gonzalez', 'Johnson', 'Martinez', 'Anderson', 'Taylor', 'Thomas'];

        return [
            'name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'position' => fake()->randomElement($positions),
            'age' => fake()->numberBetween(18, 38),
        ];
    }
}
