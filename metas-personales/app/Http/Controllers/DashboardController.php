<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGoals;
use App\Models\Goal;

class DashboardController extends Controller
{
    use AuthorizesGoals;

    public function index()
    {
        $query = $this->goalsQuery();

        $stats = [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'done' => (clone $query)->where('status', 'done')->count(),
            'avg_progress' => round((clone $query)->avg('progress') ?? 0),
        ];

        $recentGoals = (clone $query)
            ->withCount(['milestones', 'progressLogs'])
            ->latest()
            ->take(5)
            ->get();

        $categories = Goal::categories();

        return view('dashboard', compact('stats', 'recentGoals', 'categories'));
    }
}
