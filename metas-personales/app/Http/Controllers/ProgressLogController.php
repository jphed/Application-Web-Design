<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGoals;
use App\Http\Requests\StoreProgressLogRequest;
use App\Models\Goal;
use App\Models\ProgressLog;

class ProgressLogController extends Controller
{
    use AuthorizesGoals;

    public function store(StoreProgressLogRequest $request, Goal $goal)
    {
        $this->authorizeGoal($goal);

        $validated = $request->validated();

        $goal->progressLogs()->create([
            'note' => $validated['note'],
            'progress_value' => $validated['progress_value'],
            'logged_at' => $validated['logged_at'] ?? now(),
        ]);

        $goal->update(['progress' => $validated['progress_value']]);

        return back()->with('success', 'Entrada de progreso registrada.');
    }

    public function destroy(Goal $goal, ProgressLog $progressLog)
    {
        $this->authorizeGoal($goal);

        if ($progressLog->goal_id !== $goal->id) {
            abort(404);
        }

        $progressLog->delete();

        return back()->with('success', 'Entrada eliminada.');
    }
}
