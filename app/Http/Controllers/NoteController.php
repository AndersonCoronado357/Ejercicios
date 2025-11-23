<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category', 'all');

        $notes = Note::query()
            ->when($search, fn($query) => $query->search($search))
            ->byCategory($category)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        $pinnedNotes = Note::pinned()->orderBy('updated_at', 'desc')->get();
        $totalNotes = Note::count();
        $categoryCounts = Note::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        return view('notes.index', compact('notes', 'pinnedNotes', 'totalNotes', 'categoryCounts', 'search', 'category'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'color' => 'required|string',
            'is_pinned' => 'boolean'
        ]);

        Note::create($validated);

        return redirect()->route('notes.index')->with('success', 'Nota creada exitosamente');
    }

    public function show(Note $note)
    {
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'color' => 'required|string',
            'is_pinned' => 'boolean'
        ]);

        $note->update($validated);

        return redirect()->route('notes.index')->with('success', 'Nota actualizada exitosamente');
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Nota eliminada exitosamente');
    }
}
