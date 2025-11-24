@extends('layouts.app')

@section('title', 'Cronómetro')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Cronómetro</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg p-8 mb-6">
            <div class="text-center mb-6">
                <div id="display" class="text-6xl font-mono font-bold text-white mb-2">00:00:00.000</div>
                <div class="text-white text-sm opacity-80">Horas : Minutos : Segundos . Milisegundos</div>
            </div>

            <div class="flex justify-center gap-4 mb-6">
                <button id="startBtn" onclick="start()" class="px-8 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                    <i class="fas fa-play mr-2"></i>Iniciar
                </button>
                <button id="pauseBtn" onclick="pause()" disabled class="px-8 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-pause mr-2"></i>Pausar
                </button>
                <button id="resetBtn" onclick="reset()" class="px-8 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                    <i class="fas fa-redo mr-2"></i>Reiniciar
                </button>
            </div>

            <div class="flex justify-center gap-4">
                <button id="lapBtn" onclick="recordLap()" disabled class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-flag mr-2"></i>Vuelta
                </button>
                <button id="saveBtn" onclick="openSaveModal()" disabled class="px-6 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2"></i>Guardar
                </button>
            </div>
        </div>

        <div id="lapsContainer" class="mb-6 hidden">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-list mr-2"></i>Vueltas
                </h3>
                <div id="lapsList" class="space-y-2 max-h-64 overflow-y-auto">
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history mr-2"></i>Registros Guardados
            </h2>

            @forelse($records as $record)
                <div class="flex items-center justify-between p-4 bg-white rounded-lg mb-3 hover:shadow-md transition">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">
                            {{ $record->title ?? 'Sin título' }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>{{ $record->formatted_time }}
                        </p>
                        @if($record->laps && count($record->laps) > 0)
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-flag mr-1"></i>{{ count($record->laps) }} vueltas
                            </p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $record->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="flex gap-2">
                        @if($record->laps && count($record->laps) > 0)
                            <button onclick="showLaps({{ json_encode($record->laps) }}, '{{ $record->title }}')" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        @endif
                        <form action="{{ route('stopwatch.destroy', $record) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este registro?')" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-stopwatch text-4xl mb-3"></i>
                    <p>No hay registros guardados</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div id="saveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Guardar Tiempo</h2>
        <form id="saveForm">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Título (opcional)</label>
                <input type="text" id="recordTitle" placeholder="Ej: Entrenamiento de velocidad"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Tiempo: <span id="modalTime" class="font-semibold"></span>
                </p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeSaveModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<div id="lapsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <h2 class="text-xl font-bold mb-4" id="lapsModalTitle">Vueltas</h2>
        <div id="lapsModalContent" class="space-y-2 max-h-96 overflow-y-auto">
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" onclick="closeLapsModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let startTime = 0;
let elapsedTime = 0;
let timerInterval = null;
let isRunning = false;
let laps = [];

const display = document.getElementById('display');
const startBtn = document.getElementById('startBtn');
const pauseBtn = document.getElementById('pauseBtn');
const resetBtn = document.getElementById('resetBtn');
const lapBtn = document.getElementById('lapBtn');
const saveBtn = document.getElementById('saveBtn');
const lapsContainer = document.getElementById('lapsContainer');
const lapsList = document.getElementById('lapsList');

function formatTime(ms) {
    const hours = Math.floor(ms / 3600000);
    const minutes = Math.floor((ms % 3600000) / 60000);
    const seconds = Math.floor((ms % 60000) / 1000);
    const milliseconds = ms % 1000;

    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}.${String(milliseconds).padStart(3, '0')}`;
}

function updateDisplay() {
    const currentTime = Date.now();
    elapsedTime = currentTime - startTime;
    display.textContent = formatTime(elapsedTime);
}

function start() {
    if (!isRunning) {
        startTime = Date.now() - elapsedTime;
        timerInterval = setInterval(updateDisplay, 10);
        isRunning = true;

        startBtn.disabled = true;
        pauseBtn.disabled = false;
        lapBtn.disabled = false;
        saveBtn.disabled = true;
    }
}

function pause() {
    if (isRunning) {
        clearInterval(timerInterval);
        isRunning = false;

        startBtn.disabled = false;
        pauseBtn.disabled = true;
        lapBtn.disabled = true;
        saveBtn.disabled = false;
    }
}

function reset() {
    clearInterval(timerInterval);
    isRunning = false;
    elapsedTime = 0;
    startTime = 0;
    laps = [];

    display.textContent = '00:00:00.000';
    lapsList.innerHTML = '';
    lapsContainer.classList.add('hidden');

    startBtn.disabled = false;
    pauseBtn.disabled = true;
    lapBtn.disabled = true;
    saveBtn.disabled = true;
}

function recordLap() {
    if (isRunning) {
        const lapTime = elapsedTime;
        const lapNumber = laps.length + 1;
        laps.push(lapTime);

        const lapDiv = document.createElement('div');
        lapDiv.className = 'flex justify-between items-center p-3 bg-white rounded border border-gray-200';
        lapDiv.innerHTML = `
            <span class="font-semibold text-gray-700">Vuelta ${lapNumber}</span>
            <span class="text-gray-600 font-mono">${formatTime(lapTime)}</span>
        `;

        lapsList.insertBefore(lapDiv, lapsList.firstChild);
        lapsContainer.classList.remove('hidden');
    }
}

function openSaveModal() {
    document.getElementById('modalTime').textContent = formatTime(elapsedTime);
    document.getElementById('saveModal').style.display = 'flex';
}

function closeSaveModal() {
    document.getElementById('saveModal').style.display = 'none';
    document.getElementById('recordTitle').value = '';
}

function showLaps(lapsData, title) {
    document.getElementById('lapsModalTitle').textContent = title || 'Vueltas';
    const content = document.getElementById('lapsModalContent');
    content.innerHTML = '';

    lapsData.forEach((lap, index) => {
        const lapDiv = document.createElement('div');
        lapDiv.className = 'flex justify-between items-center p-3 bg-gray-50 rounded';
        lapDiv.innerHTML = `
            <span class="font-semibold text-gray-700">Vuelta ${index + 1}</span>
            <span class="text-gray-600 font-mono">${formatTime(lap)}</span>
        `;
        content.appendChild(lapDiv);
    });

    document.getElementById('lapsModal').style.display = 'flex';
}

function closeLapsModal() {
    document.getElementById('lapsModal').style.display = 'none';
}

document.getElementById('saveForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const title = document.getElementById('recordTitle').value;

    try {
        const response = await fetch('{{ route('stopwatch.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                title: title || null,
                time_milliseconds: elapsedTime,
                laps: laps.length > 0 ? laps : null
            })
        });

        const data = await response.json();

        if (data.success) {
            closeSaveModal();
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar el registro');
    }
});

document.addEventListener('keydown', function(e) {
    if (e.code === 'Space') {
        e.preventDefault();
        if (!isRunning && !startBtn.disabled) {
            start();
        } else if (isRunning && !pauseBtn.disabled) {
            pause();
        }
    } else if (e.code === 'KeyL' && !lapBtn.disabled) {
        recordLap();
    } else if (e.code === 'KeyR') {
        reset();
    }
});
</script>
@endpush
