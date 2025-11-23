@extends('layouts.app')

@section('title', 'Nueva Receta')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <a href="{{ route('recipes.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Recetas
            </a>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">Nueva Receta</h1>

        <form action="{{ route('recipes.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Título de la Receta</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                       placeholder="Ej: Pasta Carbonara">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                <textarea name="description" rows="3" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                          placeholder="Describe brevemente la receta...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                    <select name="category" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @foreach(\App\Models\Recipe::getCategories() as $key => $cat)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                {{ $cat['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Dificultad</label>
                    <select name="difficulty" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @foreach(\App\Models\Recipe::getDifficulties() as $key => $diff)
                            <option value="{{ $key }}" {{ old('difficulty') == $key ? 'selected' : '' }}>
                                {{ $diff['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Porciones</label>
                    <input type="number" name="servings" value="{{ old('servings', 4) }}" min="1" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tiempo de Preparación (min)</label>
                    <input type="number" name="prep_time" value="{{ old('prep_time', 15) }}" min="1" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tiempo de Cocción (min)</label>
                    <input type="number" name="cook_time" value="{{ old('cook_time', 30) }}" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">URL de la Imagen (opcional)</label>
                <input type="url" name="image_url" value="{{ old('image_url') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                       placeholder="https://ejemplo.com/imagen.jpg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ingredientes</label>
                <textarea name="ingredients" rows="8" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 font-mono text-sm"
                          placeholder="Escribe cada ingrediente en una línea:&#10;2 tazas de harina&#10;3 huevos&#10;1 taza de leche&#10;...">{{ old('ingredients') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Escribe cada ingrediente en una línea separada</p>
                @error('ingredients')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Instrucciones</label>
                <textarea name="instructions" rows="10" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                          placeholder="Escribe cada paso en una línea:&#10;Precalienta el horno a 180°C&#10;Mezcla los ingredientes secos&#10;Añade los huevos uno a uno&#10;...">{{ old('instructions') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Escribe cada paso en una línea separada</p>
                @error('instructions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_favorite" value="1" {{ old('is_favorite') ? 'checked' : '' }}
                           class="mr-3 w-4 h-4 text-orange-600">
                    <span class="text-gray-700 font-semibold">
                        <i class="fas fa-heart mr-2"></i>Marcar como favorita
                    </span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-bold">
                    <i class="fas fa-save mr-2"></i>Guardar Receta
                </button>
                <a href="{{ route('recipes.index') }}" class="flex-1 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-bold text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
