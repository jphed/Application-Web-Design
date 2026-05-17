<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Goal>
 */
class GoalFactory extends Factory
{
    protected $model = Goal::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => 'Meta de ejemplo: mejorar un hábito semanal',
            'description' => 'Descripción de prueba para tests automatizados.',
            'category' => 'personal',
            'deadline' => now()->addMonths(3),
            'status' => 'active',
            'progress' => 0,
        ];
    }
}
