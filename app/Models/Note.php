<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'color',
        'is_pinned'
    ];

    protected $casts = [
        'is_pinned' => 'boolean'
    ];

    public static function getCategories()
    {
        return [
            'general' => ['name' => 'General', 'icon' => 'fa-folder'],
            'work' => ['name' => 'Trabajo', 'icon' => 'fa-briefcase'],
            'personal' => ['name' => 'Personal', 'icon' => 'fa-user'],
            'ideas' => ['name' => 'Ideas', 'icon' => 'fa-lightbulb'],
            'todos' => ['name' => 'Tareas', 'icon' => 'fa-check-square'],
            'shopping' => ['name' => 'Compras', 'icon' => 'fa-shopping-cart'],
            'study' => ['name' => 'Estudio', 'icon' => 'fa-graduation-cap'],
            'health' => ['name' => 'Salud', 'icon' => 'fa-heartbeat']
        ];
    }

    public static function getColors()
    {
        return [
            'yellow' => ['name' => 'Amarillo', 'bg' => 'bg-yellow-100', 'border' => 'border-yellow-300'],
            'blue' => ['name' => 'Azul', 'bg' => 'bg-blue-100', 'border' => 'border-blue-300'],
            'green' => ['name' => 'Verde', 'bg' => 'bg-green-100', 'border' => 'border-green-300'],
            'pink' => ['name' => 'Rosa', 'bg' => 'bg-pink-100', 'border' => 'border-pink-300'],
            'purple' => ['name' => 'Morado', 'bg' => 'bg-purple-100', 'border' => 'border-purple-300'],
            'orange' => ['name' => 'Naranja', 'bg' => 'bg-orange-100', 'border' => 'border-orange-300'],
            'gray' => ['name' => 'Gris', 'bg' => 'bg-gray-100', 'border' => 'border-gray-300']
        ];
    }

    public function getCategoryNameAttribute()
    {
        return self::getCategories()[$this->category]['name'] ?? $this->category;
    }

    public function getCategoryIconAttribute()
    {
        return self::getCategories()[$this->category]['icon'] ?? 'fa-folder';
    }

    public function getColorBgAttribute()
    {
        return self::getColors()[$this->color]['bg'] ?? 'bg-yellow-100';
    }

    public function getColorBorderAttribute()
    {
        return self::getColors()[$this->color]['border'] ?? 'border-yellow-300';
    }

    public function getPreviewAttribute()
    {
        return \Illuminate\Support\Str::limit($this->content, 100);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}
