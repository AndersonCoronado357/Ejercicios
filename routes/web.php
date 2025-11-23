<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TipCalculatorController;
use App\Http\Controllers\PasswordGeneratorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MemoryGameController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\StopwatchController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
});

Route::prefix('tip-calculator')->name('tip-calculator.')->group(function () {
    Route::get('/', [TipCalculatorController::class, 'index'])->name('index');
    Route::post('/calculate', [TipCalculatorController::class, 'calculate'])->name('calculate');
});

Route::prefix('password-generator')->name('password-generator.')->group(function () {
    Route::get('/', [PasswordGeneratorController::class, 'index'])->name('index');
    Route::post('/generate', [PasswordGeneratorController::class, 'generate'])->name('generate');
});

Route::prefix('expenses')->name('expenses.')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('index');
    Route::post('/', [ExpenseController::class, 'store'])->name('store');
    Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
    Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
    Route::get('/summary', [ExpenseController::class, 'summary'])->name('summary');
});

Route::prefix('reservations')->name('reservations.')->group(function () {
    Route::get('/', [ReservationController::class, 'index'])->name('index');
    Route::post('/', [ReservationController::class, 'store'])->name('store');
    Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
    Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
    Route::post('/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('confirm');
});

Route::prefix('notes')->name('notes.')->group(function () {
    Route::get('/', [NoteController::class, 'index'])->name('index');
    Route::get('/create', [NoteController::class, 'create'])->name('create');
    Route::post('/', [NoteController::class, 'store'])->name('store');
    Route::get('/{note}', [NoteController::class, 'show'])->name('show');
    Route::get('/{note}/edit', [NoteController::class, 'edit'])->name('edit');
    Route::put('/{note}', [NoteController::class, 'update'])->name('update');
    Route::delete('/{note}', [NoteController::class, 'destroy'])->name('destroy');
});

Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [CalendarController::class, 'index'])->name('index');
    Route::post('/events', [CalendarController::class, 'store'])->name('store');
    Route::put('/events/{event}', [CalendarController::class, 'update'])->name('update');
    Route::delete('/events/{event}', [CalendarController::class, 'destroy'])->name('destroy');
    Route::get('/events', [CalendarController::class, 'getEvents'])->name('events');
});

Route::prefix('recipes')->name('recipes.')->group(function () {
    Route::get('/', [RecipeController::class, 'index'])->name('index');
    Route::get('/create', [RecipeController::class, 'create'])->name('create');
    Route::post('/', [RecipeController::class, 'store'])->name('store');
    Route::get('/{recipe}', [RecipeController::class, 'show'])->name('show');
    Route::get('/{recipe}/edit', [RecipeController::class, 'edit'])->name('edit');
    Route::put('/{recipe}', [RecipeController::class, 'update'])->name('update');
    Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('destroy');
});

Route::prefix('memory-game')->name('memory-game.')->group(function () {
    Route::get('/', [MemoryGameController::class, 'index'])->name('index');
    Route::post('/score', [MemoryGameController::class, 'saveScore'])->name('save-score');
    Route::get('/scores', [MemoryGameController::class, 'getScores'])->name('scores');
});

Route::prefix('surveys')->name('surveys.')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/create', [SurveyController::class, 'create'])->name('create');
    Route::post('/', [SurveyController::class, 'store'])->name('store');
    Route::get('/{survey}', [SurveyController::class, 'show'])->name('show');
    Route::post('/{survey}/respond', [SurveyController::class, 'respond'])->name('respond');
    Route::get('/{survey}/results', [SurveyController::class, 'results'])->name('results');
});

Route::prefix('stopwatch')->name('stopwatch.')->group(function () {
    Route::get('/', [StopwatchController::class, 'index'])->name('index');
});
