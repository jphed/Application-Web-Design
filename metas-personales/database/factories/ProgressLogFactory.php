<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\ProgressLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgressLog>
 */
class ProgressLogFactory extends Factory
{
    protected $model = ProgressLog::class;

    public function definition(): array
    {
        return [
            'goal_id' => Goal::factory(),
            'note' => 'Entrada de prueba para tests.',
            'progress_value' => 10,
            'logged_at' => now(),
        ];
    }
}
