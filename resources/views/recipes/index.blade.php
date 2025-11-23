@extends('layouts.app')

@section('title', 'Plataforma de Recetas')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Plataforma de Recetas</h1>
            <p class="text-gray-600">{{ $totalRecipes }} {{ $totalRecipes == 1 ? 'receta' : 'recetas' }} disponibles</p>
        </div>
        <a href="{{ route('recipes.create') }}" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-bold">
            <i class="fas fa-plus mr-2"></i>Nueva Receta
        </a>
    </div>

    <div class="grid lg:grid-cols-4 gap-6 mb-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
                <h3 class="font-bold text-gray-800 mb-4">
                    <i class="fas fa-filter mr-2"></i>Filtros
                </h3>

                <form method="GET" action="{{ route('recipes.index') }}">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-search mr-1"></i>Buscar
                        </label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar recetas..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-list mr-1"></i>Categoría
                        </label>
                        <select name="category" onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="all" {{ $category == 'all' ? 'selected' : '' }}>Todas</option>
                            @foreach(\App\Models\Recipe::getCategories() as $key => $cat)
                                <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>
                                    {{ $cat['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-signal mr-1"></i>Dificultad
                        </label>
                        <select name="difficulty" onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="all" {{ $difficulty == 'all' ? 'selected' : '' }}>Todas</option>
                            @foreach(\App\Models\Recipe::getDifficulties() as $key => $diff)
                                <option value="{{ $key }}" {{ $difficulty == $key ? 'selected' : '' }}>
                                    {{ $diff['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-sort mr-1"></i>Ordenar por
                        </label>
                        <select name="sort" onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="recent" {{ $sort == 'recent' ? 'selected' : '' }}>Más recientes</option>
                            <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Más populares</option>
                            <option value="favorites" {{ $sort == 'favorites' ? 'selected' : '' }}>Favoritos</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                        Aplicar Filtros
                    </button>
                </form>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-lg p-4 text-white mb-4">
                <h3 class="font-bold mb-3">
                    <i class="fas fa-chart-bar mr-2"></i>Estadísticas
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Total Recetas</span>
                        <span class="font-bold">{{ $totalRecipes }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Favoritos</span>
                        <span class="font-bold">{{ $favoriteCount }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-4">
                <h3 class="font-bold text-gray-800 mb-3">
                    <i class="fas fa-fire mr-2 text-orange-600"></i>Más Populares
                </h3>
                <div class="space-y-2">
                    @foreach($popularRecipes as $popular)
                        <a href="{{ route('recipes.show', $popular) }}" class="block p-2 hover:bg-gray-50 rounded text-sm">
                            <p class="font-semibold text-gray-800 truncate">{{ $popular->title }}</p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-eye mr-1"></i>{{ $popular->views }} vistas
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            @if($recipes->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recipes as $recipe)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                            <div class="h-48 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                                @if($recipe->image_url)
                                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-utensils text-white text-6xl"></i>
                                @endif
                            </div>

                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-gray-800 text-lg flex-1">{{ $recipe->title }}</h3>
                                    @if($recipe->is_favorite)
                                        <i class="fas fa-heart text-red-500"></i>
                                    @endif
                                </div>

                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $recipe->description }}</p>

                                <div class="flex items-center gap-2 mb-3 text-xs text-gray-500">
                                    <span class="px-2 py-1 bg-gray-100 rounded">
                                        <i class="fas {{ $recipe->category_icon }} mr-1"></i>{{ $recipe->category_name }}
                                    </span>
                                    <span class="px-2 py-1 bg-{{ $recipe->difficulty_color }}-100 text-{{ $recipe->difficulty_color }}-800 rounded">
                                        {{ $recipe->difficulty_name }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-600 mb-3">
                                    <span><i class="fas fa-clock mr-1"></i>{{ $recipe->total_time }} min</span>
                                    <span><i class="fas fa-users mr-1"></i>{{ $recipe->servings }} porciones</span>
                                    <span><i class="fas fa-eye mr-1"></i>{{ $recipe->views }}</span>
                                </div>

                                <a href="{{ route('recipes.show', $recipe) }}" class="block w-full text-center px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 transition">
                                    Ver Receta
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $recipes->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg mb-4">No se encontraron recetas</p>
                    <a href="{{ route('recipes.create') }}" class="inline-block px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                        <i class="fas fa-plus mr-2"></i>Crear Primera Receta
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
