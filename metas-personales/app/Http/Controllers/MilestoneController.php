<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGoals;
use App\Http\Requests\StoreMilestoneRequest;
use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    use AuthorizesGoals;

    public function store(StoreMilestoneRequest $request, Goal $goal)
    {
        $this->authorizeGoal($goal);

        $goal->milestones()->create([
            ...$request->validated(),
            'order' => $request->integer('order', $goal->milestones()->max('order') + 1),
            'completed' => $request->boolean('completed'),
        ]);

        return back()->with('success', 'Hito agregado.');
    }

    public function update(StoreMilestoneRequest $request, Goal $goal, Milestone $milestone)
    {
        $this->authorizeGoal($goal);
        $this->ensureMilestoneBelongsToGoal($goal, $milestone);

        $milestone->update([
            ...$request->validated(),
            'completed' => $request->boolean('completed'),
        ]);

        return back()->with('success', 'Hito actualizado.');
    }

    public function toggle(Goal $goal, Milestone $milestone)
    {
        $this->authorizeGoal($goal);
        $this->ensureMilestoneBelongsToGoal($goal, $milestone);

        $milestone->update(['completed' => ! $milestone->completed]);

        return back()->with('success', 'Estado del hito actualizado.');
    }

    public function destroy(Goal $goal, Milestone $milestone)
    {
        $this->authorizeGoal($goal);
        $this->ensureMilestoneBelongsToGoal($goal, $milestone);

        $milestone->delete();

        return back()->with('success', 'Hito eliminado.');
    }

    protected function ensureMilestoneBelongsToGoal(Goal $goal, Milestone $milestone): void
    {
        if ($milestone->goal_id !== $goal->id) {
            abort(404);
        }
    }
}
