@extends('layouts.app')

@section('title', 'Tabla de Posiciones')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('memory.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver al Menú
        </a>
    </div>

    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            <i class="fas fa-trophy text-yellow-500 mr-3"></i>Tabla de Posiciones
        </h1>
        <p class="text-gray-600">Los mejores jugadores de Memoria</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('memory.leaderboard') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-gray-700 text-sm font-bold mb-2">Filtrar por Dificultad</label>
                <select name="difficulty" onchange="this.form.submit()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="all" {{ $difficulty == 'all' ? 'selected' : '' }}>Todas</option>
                    @foreach(\App\Models\MemoryGame::getDifficulties() as $key => $diff)
                        <option value="{{ $key }}" {{ $difficulty == $key ? 'selected' : '' }}>
                            {{ $diff['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($scores->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left">Posición</th>
                            <th class="px-6 py-4 text-left">Jugador</th>
                            <th class="px-6 py-4 text-center">Dificultad</th>
                            <th class="px-6 py-4 text-center">Movimientos</th>
                            <th class="px-6 py-4 text-center">Tiempo</th>
                            <th class="px-6 py-4 text-center">Puntuación</th>
                            <th class="px-6 py-4 text-center">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scores as $index => $score)
                            <tr class="border-b hover:bg-gray-50 {{ $index < 3 ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($index === 0)
                                            <i class="fas fa-crown text-yellow-500 text-xl"></i>
                                        @elseif($index === 1)
                                            <i class="fas fa-medal text-gray-400 text-xl"></i>
                                        @elseif($index === 2)
                                            <i class="fas fa-medal text-orange-600 text-xl"></i>
                                        @endif
                                        <span class="font-bold text-gray-800">{{ $index + 1 }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $score->player_name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                                        {{ $score->difficulty_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600">{{ $score->moves }}</td>
                                <td class="px-6 py-4 text-center text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>{{ $score->formatted_time }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xl font-bold text-purple-600">{{ $score->score }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ $score->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-trophy text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg mb-4">No hay puntuaciones registradas</p>
                <a href="{{ route('memory.play') }}" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-bold">
                    <i class="fas fa-play mr-2"></i>¡Sé el primero en jugar!
                </a>
            </div>
        @endif
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('memory.play') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition font-bold text-lg shadow-lg">
            <i class="fas fa-gamepad mr-2"></i>Jugar Ahora
        </a>
    </div>
</div>
@endsection
