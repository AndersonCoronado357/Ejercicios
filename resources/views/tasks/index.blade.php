@extends('layouts.app')

@section('title', 'Lista de Tareas')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Lista de Tareas</h1>

        <form action="{{ route('tasks.store') }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="title" placeholder="Título de la tarea" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1">
                    <input type="text" name="description" placeholder="Descripción (opcional)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Agregar
                </button>
            </div>
            @error('title')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </form>

        <div class="space-y-3">
            @forelse($tasks as $task)
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition {{ $task->completed ? 'opacity-60' : '' }}">
                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="completed" value="1">
                        <button type="submit" class="w-6 h-6 rounded border-2 {{ $task->completed ? 'bg-green-500 border-green-500' : 'border-gray-300 hover:border-gray-400' }} transition">
                            @if($task->completed)
                                <i class="fas fa-check text-white text-xs"></i>
                            @endif
                        </button>
                    </form>

                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 {{ $task->completed ? 'line-through' : '' }}">
                            {{ $task->title }}
                        </h3>
                        @if($task->description)
                            <p class="text-gray-600 text-sm {{ $task->completed ? 'line-through' : '' }}">
                                {{ $task->description }}
                            </p>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <button onclick="editTask({{ $task->id }}, '{{ $task->title }}', '{{ $task->description }}')"
                                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta tarea?')"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-tasks text-4xl mb-3"></i>
                    <p>No hay tareas pendientes</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Editar Tarea</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                <input type="text" name="title" id="editTitle" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                <input type="text" name="description" id="editDescription"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editTask(id, title, description) {
        document.getElementById('editTitle').value = title;
        document.getElementById('editDescription').value = description || '';
        document.getElementById('editForm').action = `/tasks/${id}`;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endpush
