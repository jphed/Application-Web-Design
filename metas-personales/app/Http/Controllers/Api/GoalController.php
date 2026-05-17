<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\AuthorizesGoals;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    use AuthorizesGoals;

    public function index(Request $request)
    {
        $goals = $this->goalsQuery()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->latest()
            ->get();

        return response()->json(['data' => $goals]);
    }

    public function store(StoreGoalRequest $request)
    {
        $goal = $request->user()->goals()->create([
            ...$request->validated(),
            'progress' => $request->integer('progress', 0),
        ]);

        return response()->json(['data' => $goal], 201);
    }

    public function show(Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->load(['milestones', 'progressLogs', 'user']);

        return response()->json(['data' => $goal]);
    }

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->update($request->validated());

        return response()->json(['data' => $goal->fresh()]);
    }

    public function destroy(Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->delete();

        return response()->json(['message' => 'Meta eliminada.']);
    }
}
