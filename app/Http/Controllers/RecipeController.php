<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category', 'all');
        $difficulty = $request->get('difficulty', 'all');
        $sort = $request->get('sort', 'recent');

        $recipes = Recipe::query()
            ->when($search, fn($query) => $query->search($search))
            ->byCategory($category)
            ->byDifficulty($difficulty)
            ->when($sort === 'popular', fn($query) => $query->popular())
            ->when($sort === 'recent', fn($query) => $query->orderBy('created_at', 'desc'))
            ->when($sort === 'favorites', fn($query) => $query->favorites())
            ->paginate(12);

        $totalRecipes = Recipe::count();
        $favoriteCount = Recipe::where('is_favorite', true)->count();
        $popularRecipes = Recipe::orderBy('views', 'desc')->limit(5)->get();

        return view('recipes.index', compact('recipes', 'totalRecipes', 'favoriteCount', 'popularRecipes', 'search', 'category', 'difficulty', 'sort'));
    }

    public function create()
    {
        return view('recipes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty' => 'required|string',
            'prep_time' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'image_url' => 'nullable|url',
            'is_favorite' => 'nullable|boolean'
        ]);

        // Asegurar que is_favorite sea booleano
        $validated['is_favorite'] = $request->has('is_favorite') ? true : false;

        Recipe::create($validated);

        return redirect()->route('recipes.index')->with('success', 'Receta creada exitosamente');
    }

    public function show(Recipe $recipe)
    {
        // Incrementar vistas
        $recipe->increment('views');

        $relatedRecipes = Recipe::where('category', $recipe->category)
            ->where('id', '!=', $recipe->id)
            ->limit(4)
            ->get();

        return view('recipes.show', compact('recipe', 'relatedRecipes'));
    }

    public function edit(Recipe $recipe)
    {
        return view('recipes.edit', compact('recipe'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty' => 'required|string',
            'prep_time' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'image_url' => 'nullable|url',
            'is_favorite' => 'nullable|boolean'
        ]);

        // Asegurar que is_favorite sea booleano
        $validated['is_favorite'] = $request->has('is_favorite') ? true : false;

        $recipe->update($validated);

        return redirect()->route('recipes.show', $recipe)->with('success', 'Receta actualizada exitosamente');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return redirect()->route('recipes.index')->with('success', 'Receta eliminada exitosamente');
    }
}
