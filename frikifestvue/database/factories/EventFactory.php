<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = ['Anime', 'Comics', 'Videojuegos', 'Cine', 'Series TV', 'Board Games', 'Figuras Coleccionables', 'Cosplay'];
        
        return [
            'nombre' => $this->faker->catchPhrase() . ' ' . $this->faker->randomElement(['Fest', 'Con', 'Expo', 'Meetup', 'Tournament']),
            'categoria' => $this->faker->randomElement($categorias),
            'fecha' => $this->faker->dateTimeBetween('now', '+1 year'),
            'descripcion' => $this->faker->paragraph(3) . ' ' . $this->faker->sentence(),
        ];
    }
}
