<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoryGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'difficulty',
        'moves',
        'time',
        'score'
    ];

    protected $casts = [
        'moves' => 'integer',
        'time' => 'integer',
        'score' => 'integer',
    ];

    public static function getDifficulties()
    {
        return [
            'easy' => ['name' => 'Fácil', 'pairs' => 6, 'color' => 'green'],
            'medium' => ['name' => 'Medio', 'pairs' => 8, 'color' => 'yellow'],
            'hard' => ['name' => 'Difícil', 'pairs' => 12, 'color' => 'red'],
        ];
    }

    public function getDifficultyNameAttribute()
    {
        $difficulties = self::getDifficulties();
        return $difficulties[$this->difficulty]['name'] ?? $this->difficulty;
    }

    public function getFormattedTimeAttribute()
    {
        $minutes = floor($this->time / 60);
        $seconds = $this->time % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public static function getTopScores($difficulty = null, $limit = 10)
    {
        $query = self::query()->orderBy('score', 'desc')->orderBy('time', 'asc');

        if ($difficulty && $difficulty !== 'all') {
            $query->where('difficulty', $difficulty);
        }

        return $query->limit($limit)->get();
    }
}
