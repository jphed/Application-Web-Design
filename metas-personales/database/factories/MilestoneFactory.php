<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Milestone>
 */
class MilestoneFactory extends Factory
{
    protected $model = Milestone::class;

    public function definition(): array
    {
        return [
            'goal_id' => Goal::factory(),
            'title' => 'Hito de prueba',
            'due_date' => now()->addWeeks(2),
            'completed' => false,
            'order' => 0,
            'notes' => null,
        ];
    }
}
