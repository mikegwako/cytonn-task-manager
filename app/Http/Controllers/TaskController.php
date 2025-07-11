<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Show tasks (admin sees all, user sees theirs)
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $tasks = Task::with('user')->latest()->get();
        } else {
            $tasks = $user->tasks()->latest()->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    // Show create task form (admin only)
    public function create()
    {
        $this->authorizeAdmin();
        $users = User::where('role', 'user')->get();
        return view('tasks.create', compact('users'));
    }

    // Store a new task
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'title' => 'required',
            'assigned_to' => 'required|exists:users,id',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status' => 'Pending',
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    // Show edit form (admin + assigned user)
    public function edit(Task $task)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $task->assigned_to !== $user->id) {
            abort(403);
        }

        $users = User::where('role', 'user')->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    // Update task status or data
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $request->validate([
                'title' => 'required',
                'description' => 'nullable|string',
                'status' => 'required|in:Pending,In Progress,Completed',
                'assigned_to' => 'required|exists:users,id',
                'deadline' => 'nullable|date',
            ]);

            $task->update($request->only(['title', 'description', 'status', 'assigned_to', 'deadline']));
        } else {
            // user can only update status of their own task
            if ($task->assigned_to !== $user->id) {
                abort(403);
            }

            $request->validate([
                'status' => 'required|in:Pending,In Progress,Completed',
            ]);

            $task->update(['status' => $request->status]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    // Delete a task (admin only)
    public function destroy(Task $task)
    {
        $this->authorizeAdmin();
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    // Helper: only allow admin
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}
