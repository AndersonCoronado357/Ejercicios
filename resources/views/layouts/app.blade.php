<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Ejercicios de seguimiento')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">Ejercicios de seguimiento</a>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900 transition">Lista de Tareas</a>
                    <a href="{{ route('tip-calculator.index') }}" class="text-gray-600 hover:text-gray-900 transition">Calculadora de Propinas</a>
                    <a href="{{ route('password-generator.index') }}" class="text-gray-600 hover:text-gray-900 transition">Generador de Contraseñas</a>
                    <a href="{{ route('expenses.index') }}" class="text-gray-600 hover:text-gray-900 transition">Gestor de Gastos</a>
                    <a href="{{ route('reservations.index') }}" class="text-gray-600 hover:text-gray-900 transition">Sistema de Reservas</a>
                    <a href="{{ route('notes.index') }}" class="text-gray-600 hover:text-gray-900 transition">Gestor de Notas</a>
                    <a href="{{ route('calendar.index') }}" class="text-gray-600 hover:text-gray-900 transition">Calendario</a>
                    <a href="{{ route('recipes.index') }}" class="text-gray-600 hover:text-gray-900 transition">Recetas</a>
                    <a href="{{ route('memory.index') }}" class="text-gray-600 hover:text-gray-900 transition">Juego de Memoria</a>
                    <a href="{{ route('surveys.index') }}" class="text-gray-600 hover:text-gray-900 transition">Encuestas</a>
                    <a href="{{ route('stopwatch.index') }}" class="text-gray-600 hover:text-gray-900 transition">Cronómetro</a>
                </div>
                <button class="md:hidden" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
            </div>
        </div>
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-2">
                <a href="{{ route('tasks.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Lista de Tareas</a>
                <a href="{{ route('tip-calculator.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Calculadora de Propinas</a>
                <a href="{{ route('password-generator.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Generador de Contraseñas</a>
                <a href="{{ route('expenses.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Gestor de Gastos</a>
                <a href="{{ route('reservations.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Sistema de Reservas</a>
                <a href="{{ route('notes.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Gestor de Notas</a>
                <a href="{{ route('calendar.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Calendario</a>
                <a href="{{ route('recipes.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Recetas</a>
                <a href="{{ route('memory.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Juego de Memoria</a>
                <a href="{{ route('surveys.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Encuestas</a>
                <a href="{{ route('stopwatch.index') }}" class="block py-2 text-gray-600 hover:text-gray-900">Cronómetro</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
