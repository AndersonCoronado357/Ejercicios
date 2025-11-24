@extends('layouts.app')

@section('title', $survey->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $survey->title }}</h1>

        @if($survey->description)
            <p class="text-gray-600 mb-6">{{ $survey->description }}</p>
        @endif

        <form action="{{ route('surveys.respond', $survey) }}" method="POST">
            @csrf

            <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-3">Información del participante (opcional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="respondent_name" placeholder="Tu nombre"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <input type="email" name="respondent_email" placeholder="Tu correo electrónico"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($survey->questions as $index => $question)
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <label class="block text-gray-800 font-semibold mb-3">
                            {{ $index + 1 }}. {{ $question->question_text }}
                            @if($question->is_required)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($question->question_type === 'text')
                            <textarea name="question_{{ $question->id }}" rows="3" {{ $question->is_required ? 'required' : '' }}
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Escribe tu respuesta aquí..."></textarea>

                        @elseif($question->question_type === 'multiple_choice')
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center p-3 bg-white rounded-lg hover:bg-gray-100 cursor-pointer">
                                        <input type="radio" name="question_{{ $question->id }}" value="{{ $option }}" {{ $question->is_required ? 'required' : '' }}
                                               class="w-4 h-4 text-blue-600">
                                        <span class="ml-3 text-gray-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>

                        @elseif($question->question_type === 'checkbox')
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center p-3 bg-white rounded-lg hover:bg-gray-100 cursor-pointer">
                                        <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $option }}"
                                               class="w-4 h-4 text-blue-600">
                                        <span class="ml-3 text-gray-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>

                        @elseif($question->question_type === 'rating')
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="flex flex-col items-center cursor-pointer">
                                        <input type="radio" name="question_{{ $question->id }}" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}
                                               class="hidden peer">
                                        <div class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-lg peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white hover:border-blue-400 transition">
                                            {{ $i }}
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        @endif

                        @error('question_' . $question->id)
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="flex gap-4 mt-6">
                <a href="{{ route('surveys.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Volver
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-paper-plane mr-2"></i>Enviar Respuestas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
