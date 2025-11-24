@extends('layouts.app')

@section('title', 'Encuestas')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Encuestas</h1>
            <a href="{{ route('surveys.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Crear Encuesta
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($surveys as $survey)
                <div class="bg-gray-50 rounded-lg p-5 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $survey->title }}</h3>
                        <span class="px-2 py-1 text-xs rounded {{ $survey->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $survey->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    @if($survey->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $survey->description }}</p>
                    @endif

                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <span><i class="fas fa-question-circle mr-1"></i>{{ $survey->questions_count }} preguntas</span>
                        <span><i class="fas fa-users mr-1"></i>{{ $survey->responses_count }} respuestas</span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('surveys.show', $survey) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-center rounded hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-eye mr-1"></i>Responder
                        </a>
                        <a href="{{ route('surveys.results', $survey) }}" class="flex-1 px-3 py-2 bg-green-600 text-white text-center rounded hover:bg-green-700 transition text-sm">
                            <i class="fas fa-chart-bar mr-1"></i>Resultados
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fas fa-poll text-5xl mb-4"></i>
                    <p class="text-lg">No hay encuestas creadas</p>
                    <p class="text-sm">Crea tu primera encuesta para comenzar</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
