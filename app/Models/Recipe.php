<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty',
        'prep_time',
        'cook_time',
        'servings',
        'ingredients',
        'instructions',
        'image_url',
        'is_favorite',
        'views'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'views' => 'integer',
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
    ];

    // Categorías disponibles
    public static function getCategories()
    {
        return [
            'appetizer' => ['name' => 'Entrada', 'icon' => 'fa-bowl-food'],
            'main' => ['name' => 'Plato Principal', 'icon' => 'fa-drumstick-bite'],
            'dessert' => ['name' => 'Postre', 'icon' => 'fa-cake-candles'],
            'beverage' => ['name' => 'Bebida', 'icon' => 'fa-mug-hot'],
            'salad' => ['name' => 'Ensalada', 'icon' => 'fa-leaf'],
            'soup' => ['name' => 'Sopa', 'icon' => 'fa-bowl-rice'],
            'snack' => ['name' => 'Snack', 'icon' => 'fa-cookie'],
            'breakfast' => ['name' => 'Desayuno', 'icon' => 'fa-bread-slice'],
        ];
    }

    // Niveles de dificultad
    public static function getDifficulties()
    {
        return [
            'easy' => ['name' => 'Fácil', 'color' => 'green'],
            'medium' => ['name' => 'Medio', 'color' => 'yellow'],
            'hard' => ['name' => 'Difícil', 'color' => 'red'],
        ];
    }

    // Accessors
    public function getCategoryNameAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category]['name'] ?? $this->category;
    }

    public function getCategoryIconAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category]['icon'] ?? 'fa-utensils';
    }

    public function getDifficultyNameAttribute()
    {
        $difficulties = self::getDifficulties();
        return $difficulties[$this->difficulty]['name'] ?? $this->difficulty;
    }

    public function getDifficultyColorAttribute()
    {
        $difficulties = self::getDifficulties();
        return $difficulties[$this->difficulty]['color'] ?? 'gray';
    }

    public function getTotalTimeAttribute()
    {
        return $this->prep_time + $this->cook_time;
    }

    public function getIngredientsArrayAttribute()
    {
        return array_filter(explode("\n", $this->ingredients));
    }

    public function getInstructionsArrayAttribute()
    {
        return array_filter(explode("\n", $this->instructions));
    }

    // Query Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('ingredients', 'like', "%{$search}%");
        });
    }

    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        if ($difficulty && $difficulty !== 'all') {
            return $query->where('difficulty', $difficulty);
        }
        return $query;
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true)->orderBy('created_at', 'desc');
    }

    // Métodos adicionales
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function toggleFavorite()
    {
        $this->update(['is_favorite' => !$this->is_favorite]);
    }
}
