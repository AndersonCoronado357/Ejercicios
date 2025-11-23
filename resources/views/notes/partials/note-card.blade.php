<div class="bg-white {{ $note->color_bg }} border-2 {{ $note->color_border }} rounded-lg p-4 shadow hover:shadow-lg transition relative group">
    @if($note->is_pinned)
        <div class="absolute top-2 right-2">
            <i class="fas fa-thumbtack text-yellow-600"></i>
        </div>
    @endif

    <div class="mb-3">
        <h3 class="font-bold text-gray-800 text-lg mb-1 pr-6">{{ $note->title }}</h3>
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <span>
                <i class="fas {{ $note->category_icon }} mr-1"></i>{{ $note->category_name }}
            </span>
            <span>•</span>
            <span>{{ $note->updated_at->diffForHumans() }}</span>
        </div>
    </div>

    <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ $note->preview }}</p>

    <div class="flex gap-2 pt-3 border-t border-gray-300">
        <a href="{{ route('notes.show', $note) }}" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
            <i class="fas fa-eye mr-1"></i>Ver
        </a>
        <a href="{{ route('notes.edit', $note) }}" class="flex-1 text-center px-3 py-2 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 transition">
            <i class="fas fa-edit mr-1"></i>Editar
        </a>
        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="flex-1">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('¿Eliminar esta nota?')"
                    class="w-full px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                <i class="fas fa-trash mr-1"></i>
            </button>
        </form>
    </div>
</div>
