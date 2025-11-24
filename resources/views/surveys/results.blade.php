@extends('layouts.app')

@section('title', 'Resultados - ' . $survey->title)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $survey->title }}</h1>
                <p class="text-gray-600 mt-2">
                    <i class="fas fa-users mr-2"></i>
                    Total de respuestas: <span class="font-semibold">{{ $survey->responses->count() }}</span>
                </p>
            </div>
            <a href="{{ route('surveys.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        @if($survey->responses->count() === 0)
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <i class="fas fa-chart-bar text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">Aún no hay respuestas para esta encuesta</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach($results as $index => $result)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            {{ $index + 1 }}. {{ $result['question'] }}
                        </h2>

                        <p class="text-sm text-gray-600 mb-4">
                            Respuestas totales: {{ $result['total_responses'] }}
                        </p>

                        @if($result['type'] === 'text')
                            <div class="space-y-2">
                                @foreach($result['responses'] as $response)
                                    <div class="bg-white p-3 rounded border border-gray-200">
                                        <p class="text-gray-700">{{ $response }}</p>
                                    </div>
                                @endforeach
                            </div>

                        @elseif($result['type'] === 'multiple_choice' || $result['type'] === 'checkbox')
                            <div class="space-y-3">
                                @foreach($result['options'] as $option => $count)
                                    @php
                                        $percentage = $result['total_responses'] > 0 ? round(($count / $result['total_responses']) * 100, 1) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-700">{{ $option }}</span>
                                            <span class="text-gray-600 font-semibold">{{ $count }} ({{ $percentage }}%)</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @elseif($result['type'] === 'rating')
                            <div class="mb-4">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="text-4xl font-bold text-blue-600">{{ $result['average'] }}</div>
                                    <div>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= round($result['average']) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600">Calificación promedio</p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @for($i = 5; $i >= 1; $i--)
                                        @php
                                            $count = $result['distribution'][$i];
                                            $percentage = $result['total_responses'] > 0 ? round(($count / $result['total_responses']) * 100, 1) : 0;
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            <span class="w-8 text-gray-700 font-semibold">{{ $i }} <i class="fas fa-star text-yellow-400 text-xs"></i></span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-3">
                                                <div class="bg-yellow-400 h-3 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="w-20 text-right text-gray-600 text-sm">{{ $count }} ({{ $percentage }}%)</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
