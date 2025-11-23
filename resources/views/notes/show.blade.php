@extends('layouts.app')

@section('title', $note->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('notes.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Notas
            </a>
            <div class="flex gap-2">
                <a href="{{ route('notes.edit', $note) }}" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Eliminar esta nota?')"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-800">{{ $note->title }}</h1>
                @if($note->is_pinned)
                    <span class="text-yellow-600">
                        <i class="fas fa-thumbtack text-xl"></i>
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <span class="px-3 py-1 {{ $note->color_bg }} {{ $note->color_border }} border rounded-full">
                    <i class="fas {{ $note->category_icon }} mr-1"></i>{{ $note->category_name }}
                </span>
                <span>
                    <i class="fas fa-clock mr-1"></i>Creada {{ $note->created_at->diffForHumans() }}
                </span>
                <span>
                    <i class="fas fa-edit mr-1"></i>Actualizada {{ $note->updated_at->diffForHumans() }}
                </span>
            </div>
        </div>

        <div class="prose max-w-none">
            <div class="bg-gray-50 rounded-lg p-6 border-l-4 {{ $note->color_border }}">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $note->content }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
