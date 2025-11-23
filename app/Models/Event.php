<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'color',
        'all_day',
        'reminder',
        'reminder_minutes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'all_day' => 'boolean',
        'reminder' => 'boolean'
    ];

    public static function getColors()
    {
        return [
            'blue' => ['name' => 'Azul', 'class' => 'bg-blue-500'],
            'red' => ['name' => 'Rojo', 'class' => 'bg-red-500'],
            'green' => ['name' => 'Verde', 'class' => 'bg-green-500'],
            'yellow' => ['name' => 'Amarillo', 'class' => 'bg-yellow-500'],
            'purple' => ['name' => 'Morado', 'class' => 'bg-purple-500'],
            'pink' => ['name' => 'Rosa', 'class' => 'bg-pink-500'],
            'indigo' => ['name' => 'Índigo', 'class' => 'bg-indigo-500'],
            'orange' => ['name' => 'Naranja', 'class' => 'bg-orange-500'],
            'teal' => ['name' => 'Verde Azulado', 'class' => 'bg-teal-500'],
            'gray' => ['name' => 'Gris', 'class' => 'bg-gray-500']
        ];
    }

    public static function getReminderOptions()
    {
        return [
            5 => '5 minutos antes',
            15 => '15 minutos antes',
            30 => '30 minutos antes',
            60 => '1 hora antes',
            120 => '2 horas antes',
            1440 => '1 día antes',
            2880 => '2 días antes',
            10080 => '1 semana antes'
        ];
    }

    public function getColorClassAttribute()
    {
        return self::getColors()[$this->color]['class'] ?? 'bg-blue-500';
    }

    public function getColorNameAttribute()
    {
        return self::getColors()[$this->color]['name'] ?? 'Azul';
    }

    public function getDurationAttribute()
    {
        $diff = $this->start_date->diffInMinutes($this->end_date);

        if ($diff < 60) {
            return $diff . ' min';
        } elseif ($diff < 1440) {
            $hours = floor($diff / 60);
            $minutes = $diff % 60;
            return $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'min' : '');
        } else {
            $days = floor($diff / 1440);
            return $days . ' ' . ($days == 1 ? 'día' : 'días');
        }
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())
                     ->orderBy('start_date', 'asc');
    }

    public function scopeInMonth($query, $year, $month)
    {
        return $query->whereYear('start_date', $year)
                     ->whereMonth('start_date', $month);
    }

    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('start_date', '<=', $date)
                     ->whereDate('end_date', '>=', $date);
    }
}
