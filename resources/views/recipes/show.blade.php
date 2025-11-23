@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('recipes.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Recetas
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="h-96 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
            @if($recipe->image_url)
                <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
            @else
                <i class="fas fa-utensils text-white text-9xl"></i>
            @endif
        </div>

        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $recipe->title }}</h1>
                    <p class="text-gray-600 text-lg">{{ $recipe->description }}</p>
                </div>
                @if($recipe->is_favorite)
                    <i class="fas fa-heart text-red-500 text-3xl ml-4"></i>
                @endif
            </div>

            <div class="grid md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <i class="fas {{ $recipe->category_icon }} text-orange-600 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Categoría</p>
                    <p class="font-bold text-gray-800">{{ $recipe->category_name }}</p>
                </div>
                <div class="bg-{{ $recipe->difficulty_color }}-100 p-4 rounded-lg text-center">
                    <i class="fas fa-signal text-{{ $recipe->difficulty_color }}-600 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Dificultad</p>
                    <p class="font-bold text-gray-800">{{ $recipe->difficulty_name }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <i class="fas fa-clock text-orange-600 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Tiempo Total</p>
                    <p class="font-bold text-gray-800">{{ $recipe->total_time }} min</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <i class="fas fa-users text-orange-600 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Porciones</p>
                    <p class="font-bold text-gray-800">{{ $recipe->servings }}</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-list-ul mr-2 text-orange-600"></i>Ingredientes
                    </h2>
                    <ul class="space-y-2">
                        @foreach($recipe->ingredients_array as $ingredient)
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-3 mt-1"></i>
                                <span class="text-gray-700">{{ $ingredient }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-list-ol mr-2 text-orange-600"></i>Instrucciones
                    </h2>
                    <ol class="space-y-3">
                        @foreach($recipe->instructions_array as $index => $instruction)
                            <li class="flex">
                                <span class="flex-shrink-0 w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-gray-700 pt-1">{{ $instruction }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            <div class="flex gap-4 pt-6 border-t">
                <a href="{{ route('recipes.edit', $recipe) }}" class="flex-1 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-center">
                    <i class="fas fa-edit mr-2"></i>Editar Receta
                </a>
                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de eliminar esta receta?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold">
                        <i class="fas fa-trash mr-2"></i>Eliminar Receta
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($relatedRecipes->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Recetas Relacionadas</h2>
            <div class="grid md:grid-cols-4 gap-6">
                @foreach($relatedRecipes as $related)
                    <a href="{{ route('recipes.show', $related) }}" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="h-40 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                            @if($related->image_url)
                                <img src="{{ $related->image_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-utensils text-white text-4xl"></i>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 mb-2">{{ $related->title }}</h3>
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-clock mr-1"></i>{{ $related->total_time }} min
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
