<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Goal;

trait AuthorizesGoals
{
    protected function authorizeGoal(Goal $goal): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && $goal->user_id !== $user->id) {
            abort(403, 'No tienes permiso para acceder a esta meta.');
        }
    }

    protected function goalsQuery()
    {
        $user = auth()->user();

        return $user->isAdmin()
            ? Goal::query()->with('user')
            : $user->goals();
    }
}
