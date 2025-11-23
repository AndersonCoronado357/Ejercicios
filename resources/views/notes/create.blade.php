@extends('layouts.app')

@section('title', 'Nueva Nota')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <a href="{{ route('notes.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Notas
            </a>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">Nueva Nota</h1>

        <form action="{{ route('notes.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                       placeholder="Título de la nota">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Contenido</label>
                <textarea name="content" rows="12" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                          placeholder="Escribe el contenido de tu nota aquí...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                    <select name="category" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        @foreach(\App\Models\Note::getCategories() as $key => $cat)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                {{ $cat['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                    <select name="color" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        @foreach(\App\Models\Note::getColors() as $key => $color)
                            <option value="{{ $key }}" {{ old('color', 'yellow') == $key ? 'selected' : '' }}>
                                {{ $color['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}
                           class="mr-3 w-4 h-4 text-yellow-600">
                    <span class="text-gray-700 font-semibold">
                        <i class="fas fa-thumbtack mr-2"></i>Fijar esta nota
                    </span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-bold">
                    <i class="fas fa-save mr-2"></i>Guardar Nota
                </button>
                <a href="{{ route('notes.index') }}" class="flex-1 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-bold text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
