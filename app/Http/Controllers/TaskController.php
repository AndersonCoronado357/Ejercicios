<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente');
    }

    public function update(Request $request, Task $task)
    {
        if ($request->has('completed')) {
            $task->update(['completed' => !$task->completed]);
            $message = $task->completed ? 'Tarea marcada como completada' : 'Tarea marcada como pendiente';
        } else {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);
            $task->update($validated);
            $message = 'Tarea actualizada exitosamente';
        }

        return redirect()->route('tasks.index')->with('success', $message);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada exitosamente');
    }
}
