<?php

namespace App\Http\Controllers;

use App\Models\StopwatchRecord;
use Illuminate\Http\Request;

class StopwatchController extends Controller
{
    public function index()
    {
        $records = StopwatchRecord::latest()->take(10)->get();
        return view('stopwatch.index', compact('records'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'time_milliseconds' => 'required|integer|min:0',
            'laps' => 'nullable|array'
        ]);

        StopwatchRecord::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tiempo guardado exitosamente'
        ]);
    }

    public function destroy(StopwatchRecord $record)
    {
        $record->delete();
        return redirect()->route('stopwatch.index')->with('success', 'Registro eliminado exitosamente');
    }
}
