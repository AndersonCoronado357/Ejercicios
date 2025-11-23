@extends('layouts.app')

@section('title', 'Inicio - Proyecto MVC')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Ejercicios de seguimiento</h1>
        <p class="text-xl text-gray-600">Selecciona una aplicación para comenzar</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('tasks.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-blue-600 text-4xl mb-4">
                <i class="fas fa-tasks"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Lista de Tareas</h2>
            <p class="text-gray-600">Administra tus tareas pendientes, márcalas como completadas y mantén tu productividad.</p>
        </a>

        <a href="{{ route('tip-calculator.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-green-600 text-4xl mb-4">
                <i class="fas fa-calculator"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Calculadora de Propinas</h2>
            <p class="text-gray-600">Calcula automáticamente la propina basada en el monto total y el porcentaje deseado.</p>
        </a>

        <a href="{{ route('password-generator.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-purple-600 text-4xl mb-4">
                <i class="fas fa-key"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Generador de Contraseñas</h2>
            <p class="text-gray-600">Genera contraseñas seguras y personalizables para proteger tus cuentas.</p>
        </a>

        <a href="{{ route('expenses.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-red-600 text-4xl mb-4">
                <i class="fas fa-wallet"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Gestor de Gastos</h2>
            <p class="text-gray-600">Registra y categoriza tus gastos diarios con resúmenes mensuales.</p>
        </a>

        <a href="{{ route('reservations.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-indigo-600 text-4xl mb-4">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Sistema de Reservas</h2>
            <p class="text-gray-600">Gestiona citas y servicios con disponibilidad en tiempo real.</p>
        </a>

        <a href="{{ route('notes.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-yellow-600 text-4xl mb-4">
                <i class="fas fa-sticky-note"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Gestor de Notas</h2>
            <p class="text-gray-600">Crea y organiza tus notas en categorías con búsqueda rápida.</p>
        </a>

        <a href="{{ route('calendar.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-teal-600 text-4xl mb-4">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Calendario de Eventos</h2>
            <p class="text-gray-600">Calendario interactivo con eventos, recordatorios y notificaciones.</p>
        </a>

        <a href="{{ route('recipes.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-orange-600 text-4xl mb-4">
                <i class="fas fa-utensils"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Plataforma de Recetas</h2>
            <p class="text-gray-600">Busca, guarda y comparte recetas de cocina con filtros avanzados.</p>
        </a>

        <a href="{{ route('memory.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-pink-600 text-4xl mb-4">
                <i class="fas fa-gamepad"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Juego de Memoria</h2>
            <p class="text-gray-600">Juego clásico de memoria con niveles de dificultad y puntajes.</p>
        </a>

        <a href="{{ route('surveys.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-cyan-600 text-4xl mb-4">
                <i class="fas fa-poll"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Plataforma de Encuestas</h2>
            <p class="text-gray-600">Crea encuestas, analiza resultados y genera informes visuales.</p>
        </a>

        <a href="{{ route('stopwatch.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-gray-600 text-4xl mb-4">
                <i class="fas fa-stopwatch"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Cronómetro Online</h2>
            <p class="text-gray-600">Cronómetro con funciones de inicio, pausa y registro de vueltas.</p>
        </a>
    </div>
</div>
@endsection
