<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopwatchRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'time_milliseconds',
        'laps'
    ];

    protected $casts = [
        'laps' => 'array'
    ];

    public function getFormattedTimeAttribute()
    {
        $ms = $this->time_milliseconds;
        $hours = floor($ms / 3600000);
        $minutes = floor(($ms % 3600000) / 60000);
        $seconds = floor(($ms % 60000) / 1000);
        $milliseconds = $ms % 1000;

        return sprintf('%02d:%02d:%02d.%03d', $hours, $minutes, $seconds, $milliseconds);
    }
}
