@extends('layouts.app')

@section('title', 'Jugar Memoria')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('memory.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver al Menú
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="grid md:grid-cols-4 gap-4 mb-6">
            <div class="bg-purple-100 p-4 rounded-lg text-center">
                <p class="text-sm text-purple-600 mb-1">Dificultad</p>
                <p class="text-xl font-bold text-purple-800" id="difficultyDisplay"></p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg text-center">
                <p class="text-sm text-blue-600 mb-1">Movimientos</p>
                <p class="text-xl font-bold text-blue-800" id="moves">0</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg text-center">
                <p class="text-sm text-green-600 mb-1">Tiempo</p>
                <p class="text-xl font-bold text-green-800" id="timer">00:00</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg text-center">
                <p class="text-sm text-yellow-600 mb-1">Puntuación</p>
                <p class="text-xl font-bold text-yellow-800" id="score">0</p>
            </div>
        </div>

        <div class="flex justify-center gap-4 mb-6">
            <button id="startBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold">
                <i class="fas fa-play mr-2"></i>Iniciar Juego
            </button>
            <button id="resetBtn" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-bold">
                <i class="fas fa-redo mr-2"></i>Reiniciar
            </button>
        </div>

        <div id="gameBoard" class="grid gap-4 justify-center">
            <!-- Las cartas se generarán aquí dinámicamente -->
        </div>
    </div>
</div>

<!-- Modal de Victoria -->
<div id="winModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <i class="fas fa-trophy text-yellow-500 text-6xl mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">¡Felicidades!</h2>
            <p class="text-gray-600">Has completado el juego</p>
        </div>

        <div class="bg-gray-100 rounded-lg p-4 mb-6 space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Movimientos:</span>
                <span class="font-bold" id="finalMoves"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Tiempo:</span>
                <span class="font-bold" id="finalTime"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Puntuación:</span>
                <span class="font-bold text-purple-600 text-xl" id="finalScore"></span>
            </div>
        </div>

        <form id="saveScoreForm">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tu Nombre</label>
                <input type="text" id="playerName" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                       placeholder="Ingresa tu nombre">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-bold">
                    <i class="fas fa-save mr-2"></i>Guardar Puntuación
                </button>
                <button type="button" id="closeModal" class="flex-1 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-bold">
                    Cerrar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const difficulty = '{{ $difficulty }}';
const difficulties = @json(\App\Models\MemoryGame::getDifficulties());

// Emojis para las cartas
const cardEmojis = ['🍎', '🍌', '🍇', '🍊', '🍓', '🍒', '🥝', '🍑', '🥭', '🍍', '🥥', '🥑', '🍆', '🥕', '🌽', '🥦'];

let cards = [];
let flippedCards = [];
let matchedPairs = 0;
let moves = 0;
let timeElapsed = 0;
let timerInterval = null;
let gameStarted = false;
let canFlip = true;

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('difficultyDisplay').textContent = difficulties[difficulty].name;
    setupGame();
});

function setupGame() {
    const pairs = difficulties[difficulty].pairs;
    const selectedEmojis = cardEmojis.slice(0, pairs);

    // Duplicar y mezclar
    cards = [...selectedEmojis, ...selectedEmojis].sort(() => Math.random() - 0.5);

    // Ajustar grid según dificultad
    const cols = difficulty === 'hard' ? 6 : 4;
    const gameBoard = document.getElementById('gameBoard');
    gameBoard.style.gridTemplateColumns = `repeat(${cols}, minmax(0, 1fr))`;
    gameBoard.innerHTML = '';

    // Crear cartas
    cards.forEach((emoji, index) => {
        const card = document.createElement('div');
        card.className = 'card w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg flex items-center justify-center text-4xl cursor-pointer transform transition hover:scale-105';
        card.dataset.index = index;
        card.dataset.emoji = emoji;
        card.innerHTML = '<i class="fas fa-question text-white"></i>';
        card.addEventListener('click', flipCard);
        gameBoard.appendChild(card);
    });
}

function startGame() {
    gameStarted = true;
    moves = 0;
    matchedPairs = 0;
    timeElapsed = 0;
    updateDisplay();

    timerInterval = setInterval(() => {
        timeElapsed++;
        updateDisplay();
    }, 1000);

    document.getElementById('startBtn').disabled = true;
}

function flipCard(e) {
    if (!gameStarted) {
        startGame();
    }

    if (!canFlip || flippedCards.length === 2) return;

    const card = e.currentTarget;
    if (card.classList.contains('matched') || card.classList.contains('flipped')) return;

    card.classList.add('flipped');
    card.innerHTML = card.dataset.emoji;
    card.style.backgroundColor = '#fff';
    flippedCards.push(card);

    if (flippedCards.length === 2) {
        canFlip = false;
        moves++;
        updateDisplay();
        checkMatch();
    }
}

function checkMatch() {
    const [card1, card2] = flippedCards;

    if (card1.dataset.emoji === card2.dataset.emoji) {
        // Match!
        setTimeout(() => {
            card1.classList.add('matched');
            card2.classList.add('matched');
            card1.style.opacity = '0.5';
            card2.style.opacity = '0.5';
            matchedPairs++;
            flippedCards = [];
            canFlip = true;

            if (matchedPairs === difficulties[difficulty].pairs) {
                endGame();
            }
        }, 500);
    } else {
        // No match
        setTimeout(() => {
            card1.classList.remove('flipped');
            card2.classList.remove('flipped');
            card1.innerHTML = '<i class="fas fa-question text-white"></i>';
            card2.innerHTML = '<i class="fas fa-question text-white"></i>';
            card1.style.backgroundColor = '';
            card2.style.backgroundColor = '';
            flippedCards = [];
            canFlip = true;
        }, 1000);
    }
}

function updateDisplay() {
    document.getElementById('moves').textContent = moves;
    const minutes = Math.floor(timeElapsed / 60);
    const seconds = timeElapsed % 60;
    document.getElementById('timer').textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

    const score = calculateScore();
    document.getElementById('score').textContent = score;
}

function calculateScore() {
    const pairs = difficulties[difficulty].pairs;
    const maxScore = pairs * 100;
    const timePenalty = Math.floor(timeElapsed / 2);
    const movePenalty = moves > pairs ? (moves - pairs) * 5 : 0;
    return Math.max(0, maxScore - timePenalty - movePenalty);
}

function endGame() {
    clearInterval(timerInterval);
    const score = calculateScore();

    document.getElementById('finalMoves').textContent = moves;
    document.getElementById('finalTime').textContent = document.getElementById('timer').textContent;
    document.getElementById('finalScore').textContent = score;
    document.getElementById('winModal').classList.remove('hidden');
}

document.getElementById('resetBtn').addEventListener('click', function() {
    clearInterval(timerInterval);
    gameStarted = false;
    canFlip = true;
    flippedCards = [];
    matchedPairs = 0;
    moves = 0;
    timeElapsed = 0;
    updateDisplay();
    setupGame();
    document.getElementById('startBtn').disabled = false;
});

document.getElementById('startBtn').addEventListener('click', startGame);

document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('winModal').classList.add('hidden');
    window.location.href = '{{ route("memory.index") }}';
});

document.getElementById('saveScoreForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const playerName = document.getElementById('playerName').value;
    const score = calculateScore();

    try {
        const response = await fetch('{{ route("memory.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                player_name: playerName,
                difficulty: difficulty,
                moves: moves,
                time: timeElapsed,
                score: score
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('¡Puntuación guardada exitosamente!');
            window.location.href = '{{ route("memory.leaderboard") }}?difficulty=' + difficulty;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar la puntuación');
    }
});
</script>

<style>
.card {
    aspect-ratio: 1;
}
</style>
@endsection
