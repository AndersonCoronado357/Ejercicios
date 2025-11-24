@extends('layouts.app')

@section('title', 'Crear Encuesta')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Crear Nueva Encuesta</h1>

        <form id="surveyForm" action="{{ route('surveys.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Título de la Encuesta</label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción (opcional)</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Preguntas</h2>
                    <button type="button" onclick="addQuestion()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>Agregar Pregunta
                    </button>
                </div>

                <div id="questionsContainer" class="space-y-4">
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('surveys.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Crear Encuesta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let questionCount = 0;

function addQuestion() {
    const container = document.getElementById('questionsContainer');
    const questionDiv = document.createElement('div');
    questionDiv.className = 'bg-gray-50 p-4 rounded-lg border border-gray-200';
    questionDiv.id = `question-${questionCount}`;

    questionDiv.innerHTML = `
        <div class="flex justify-between items-start mb-3">
            <h3 class="font-semibold text-gray-700">Pregunta ${questionCount + 1}</h3>
            <button type="button" onclick="removeQuestion(${questionCount})" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="mb-3">
            <input type="text" name="questions[${questionCount}][question_text]" placeholder="Escribe tu pregunta" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-3">
            <select name="questions[${questionCount}][question_type]" onchange="handleTypeChange(${questionCount}, this.value)" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Selecciona tipo de pregunta</option>
                <option value="text">Texto libre</option>
                <option value="multiple_choice">Opción múltiple</option>
                <option value="checkbox">Casillas de verificación</option>
                <option value="rating">Calificación (1-5)</option>
            </select>
        </div>

        <div id="options-${questionCount}" class="mb-3 hidden">
            <label class="block text-sm text-gray-600 mb-2">Opciones (una por línea)</label>
            <textarea name="questions[${questionCount}][options_text]" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Opción 1&#10;Opción 2&#10;Opción 3"></textarea>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="questions[${questionCount}][is_required]" value="1" id="required-${questionCount}"
                   class="w-4 h-4 text-blue-600">
            <label for="required-${questionCount}" class="ml-2 text-sm text-gray-700">Pregunta obligatoria</label>
        </div>
    `;

    container.appendChild(questionDiv);
    questionCount++;
}

function removeQuestion(index) {
    const questionDiv = document.getElementById(`question-${index}`);
    if (questionDiv) {
        questionDiv.remove();
    }
}

function handleTypeChange(index, type) {
    const optionsDiv = document.getElementById(`options-${index}`);
    if (type === 'multiple_choice' || type === 'checkbox') {
        optionsDiv.classList.remove('hidden');
    } else {
        optionsDiv.classList.add('hidden');
    }
}

document.getElementById('surveyForm').addEventListener('submit', function(e) {
    const questions = document.querySelectorAll('[id^="question-"]');

    questions.forEach((questionDiv, index) => {
        const optionsTextarea = questionDiv.querySelector('[name*="[options_text]"]');
        const typeSelect = questionDiv.querySelector('[name*="[question_type]"]');

        if (optionsTextarea && (typeSelect.value === 'multiple_choice' || typeSelect.value === 'checkbox')) {
            const optionsText = optionsTextarea.value.trim();
            if (optionsText) {
                const optionsArray = optionsText.split('\n').filter(opt => opt.trim());
                optionsArray.forEach((option, optIndex) => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `questions[${index}][options][${optIndex}]`;
                    hiddenInput.value = option.trim();
                    questionDiv.appendChild(hiddenInput);
                });
            }
        }
    });
});

addQuestion();
</script>
@endpush
