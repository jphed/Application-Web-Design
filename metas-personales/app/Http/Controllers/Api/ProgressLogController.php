<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\AuthorizesGoals;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgressLogRequest;
use App\Models\Goal;

class ProgressLogController extends Controller
{
    use AuthorizesGoals;

    public function index(Goal $goal)
    {
        $this->authorizeGoal($goal);

        $logs = $goal->progressLogs()->orderByDesc('logged_at')->get();

        return response()->json(['data' => $logs]);
    }

    public function store(StoreProgressLogRequest $request, Goal $goal)
    {
        $this->authorizeGoal($goal);

        $validated = $request->validated();

        $log = $goal->progressLogs()->create([
            'note' => $validated['note'],
            'progress_value' => $validated['progress_value'],
            'logged_at' => $validated['logged_at'] ?? now(),
        ]);

        $goal->update(['progress' => $validated['progress_value']]);

        return response()->json(['data' => $log], 201);
    }
}
