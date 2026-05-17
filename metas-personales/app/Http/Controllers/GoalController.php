<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGoals;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    use AuthorizesGoals;

    public function index(Request $request)
    {
        $query = $this->goalsQuery()->with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $goals = $query->latest()->paginate(10)->withQueryString();
        $categories = Goal::categories();

        return view('goals.index', compact('goals', 'categories'));
    }

    public function create()
    {
        $categories = Goal::categories();

        return view('goals.create', compact('categories'));
    }

    public function store(StoreGoalRequest $request)
    {
        $goal = auth()->user()->goals()->create([
            ...$request->validated(),
            'progress' => $request->integer('progress', 0),
        ]);

        return redirect()
            ->route('goals.show', $goal)
            ->with('success', 'Meta creada correctamente.');
    }

    public function show(Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->load(['milestones', 'progressLogs', 'user']);

        return view('goals.show', compact('goal'));
    }

    public function edit(Goal $goal)
    {
        $this->authorizeGoal($goal);

        $categories = Goal::categories();

        return view('goals.edit', compact('goal', 'categories'));
    }

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->update($request->validated());

        return redirect()
            ->route('goals.show', $goal)
            ->with('success', 'Meta actualizada.');
    }

    public function destroy(Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->delete();

        return redirect()
            ->route('goals.index')
            ->with('success', 'Meta eliminada.');
    }
}
