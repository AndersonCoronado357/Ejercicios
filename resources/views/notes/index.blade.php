@extends('layouts.app')

@section('title', 'Gestor de Notas')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestor de Notas</h1>
            <p class="text-gray-600">{{ $totalNotes }} {{ $totalNotes == 1 ? 'nota' : 'notas' }} en total</p>
        </div>
        <a href="{{ route('notes.create') }}" class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-bold">
            <i class="fas fa-plus mr-2"></i>Nueva Nota
        </a>
    </div>

    <div class="grid lg:grid-cols-4 gap-6 mb-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-4">
                <h3 class="font-bold text-gray-800 mb-4">
                    <i class="fas fa-filter mr-2"></i>Filtros
                </h3>

                <form method="GET" action="{{ route('notes.index') }}">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-search mr-1"></i>Buscar
                        </label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar notas..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-folder mr-1"></i>Categoría
                        </label>
                        <select name="category" onchange="this.form.submit()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="all" {{ $category == 'all' ? 'selected' : '' }}>Todas</option>
                            @foreach(\App\Models\Note::getCategories() as $key => $cat)
                                <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>
                                    {{ $cat['name'] }} ({{ $categoryCounts[$key] ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Aplicar Filtros
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-bold text-gray-700 mb-3">
                        <i class="fas fa-chart-pie mr-2"></i>Estadísticas
                    </h4>
                    <div class="space-y-2 text-sm">
                        @foreach(\App\Models\Note::getCategories() as $key => $cat)
                            @php $count = $categoryCounts[$key] ?? 0; @endphp
                            @if($count > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">
                                        <i class="fas {{ $cat['icon'] }} mr-1"></i>{{ $cat['name'] }}
                                    </span>
                                    <span class="font-bold text-gray-800">{{ $count }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            @if($pinnedNotes->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        <i class="fas fa-thumbtack mr-2 text-yellow-600"></i>Notas Fijadas
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pinnedNotes as $note)
                            @include('notes.partials.note-card', ['note' => $note])
                        @endforeach
                    </div>
                </div>
            @endif

            <h3 class="text-lg font-bold text-gray-800 mb-3">
                <i class="fas fa-sticky-note mr-2"></i>Todas las Notas
            </h3>

            @if($notes->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($notes as $note)
                        @include('notes.partials.note-card', ['note' => $note])
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <i class="fas fa-sticky-note text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg mb-4">No se encontraron notas</p>
                    <a href="{{ route('notes.create') }}" class="inline-block px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                        <i class="fas fa-plus mr-2"></i>Crear Primera Nota
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
