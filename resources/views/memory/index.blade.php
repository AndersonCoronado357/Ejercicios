@extends('layouts.app')

@section('title', 'Juego de Memoria')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">
            <i class="fas fa-brain text-purple-600 mr-3"></i>Juego de Memoria
        </h1>
        <p class="text-gray-600 text-lg">¡Desafía tu mente y encuentra todas las parejas!</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-12">
        @foreach(\App\Models\MemoryGame::getDifficulties() as $key => $diff)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                <div class="bg-gradient-to-br from-{{ $diff['color'] }}-400 to-{{ $diff['color'] }}-600 p-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">{{ $diff['name'] }}</h3>
                    <p class="text-{{ $diff['color'] }}-100">{{ $diff['pairs'] }} pares de cartas</p>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-layer-group mr-2"></i>Total de cartas: <strong>{{ $diff['pairs'] * 2 }}</strong>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-trophy mr-2"></i>Puntos posibles: <strong>{{ $diff['pairs'] * 100 }}</strong>
                        </p>
                    </div>
                    <a href="{{ route('memory.play', ['difficulty' => $key]) }}"
                       class="block w-full text-center px-6 py-3 bg-{{ $diff['color'] }}-600 text-white rounded-lg hover:bg-{{ $diff['color'] }}-700 transition font-bold">
                        <i class="fas fa-play mr-2"></i>Jugar
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top 10 Global
            </h2>
            @if($topScores->count() > 0)
                <div class="space-y-3">
                    @foreach($topScores as $index => $score)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white font-bold flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $score->player_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        <span class="px-2 py-1 bg-gray-200 rounded">{{ $score->difficulty_name }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-purple-600">{{ $score->score }} pts</p>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>{{ $score->formatted_time }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-trophy text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">¡Sé el primero en jugar!</p>
                </div>
            @endif
            <a href="{{ route('memory.leaderboard') }}" class="block mt-4 text-center text-purple-600 hover:text-purple-800 font-semibold">
                Ver tabla completa <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
            <h2 class="text-2xl font-bold mb-6">
                <i class="fas fa-info-circle mr-2"></i>Cómo Jugar
            </h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center font-bold">
                        1
                    </div>
                    <p>Selecciona un nivel de dificultad</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center font-bold">
                        2
                    </div>
                    <p>Haz clic en las cartas para voltearlas</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center font-bold">
                        3
                    </div>
                    <p>Encuentra todas las parejas en el menor tiempo posible</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center font-bold">
                        4
                    </div>
                    <p>¡Compite por el mejor puntaje!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-green-600 mb-4">
                <i class="fas fa-medal mr-2"></i>Top Fácil
            </h3>
            @foreach($easyScores as $score)
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-800">{{ $score->player_name }}</span>
                    <span class="font-bold text-green-600">{{ $score->score }}</span>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-yellow-600 mb-4">
                <i class="fas fa-medal mr-2"></i>Top Medio
            </h3>
            @foreach($mediumScores as $score)
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-800">{{ $score->player_name }}</span>
                    <span class="font-bold text-yellow-600">{{ $score->score }}</span>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4">
                <i class="fas fa-medal mr-2"></i>Top Difícil
            </h3>
            @foreach($hardScores as $score)
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-800">{{ $score->player_name }}</span>
                    <span class="font-bold text-red-600">{{ $score->score }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
