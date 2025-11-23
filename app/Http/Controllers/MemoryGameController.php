<?php

namespace App\Http\Controllers;

use App\Models\MemoryGame;
use Illuminate\Http\Request;

class MemoryGameController extends Controller
{
    public function index()
    {
        $topScores = MemoryGame::getTopScores('all', 10);
        $easyScores = MemoryGame::getTopScores('easy', 5);
        $mediumScores = MemoryGame::getTopScores('medium', 5);
        $hardScores = MemoryGame::getTopScores('hard', 5);

        return view('memory.index', compact('topScores', 'easyScores', 'mediumScores', 'hardScores'));
    }

    public function play(Request $request)
    {
        $difficulty = $request->get('difficulty', 'easy');
        return view('memory.play', compact('difficulty'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'player_name' => 'required|string|max:255',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'moves' => 'required|integer|min:0',
            'time' => 'required|integer|min:0',
            'score' => 'required|integer|min:0'
        ]);

        MemoryGame::create($validated);

        return response()->json([
            'success' => true,
            'message' => '¡Puntuación guardada exitosamente!'
        ]);
    }

    public function leaderboard(Request $request)
    {
        $difficulty = $request->get('difficulty', 'all');
        $scores = MemoryGame::getTopScores($difficulty, 20);

        return view('memory.leaderboard', compact('scores', 'difficulty'));
    }
}
