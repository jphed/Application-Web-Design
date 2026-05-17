<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $query = Goal::query()->with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $goals = $query->latest()->paginate(15)->withQueryString();
        $categories = Goal::categories();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.goals.index', compact('goals', 'categories', 'users'));
    }
}
