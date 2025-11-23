@extends('layouts.app')

@section('title', 'Gestor de Gastos')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestor de Gastos</h1>
            <a href="{{ route('expenses.summary') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-chart-pie mr-2"></i>Ver Resumen Anual
            </a>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2">
                <form action="{{ route('expenses.store') }}" method="POST" class="bg-gray-50 rounded-lg p-4">
                    @csrf
                    <h3 class="font-bold text-gray-800 mb-4">Registrar Nuevo Gasto</h3>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                            <input type="text" name="description" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Monto</label>
                            <input type="number" name="amount" step="0.01" min="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                            <select name="category" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                @foreach(\App\Models\Expense::getCategories() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                            <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Método de Pago</label>
                            <select name="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="">Seleccionar...</option>
                                @foreach(\App\Models\Expense::getPaymentMethods() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Notas</label>
                            <input type="text" name="notes"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <button type="submit" class="mt-4 w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-plus mr-2"></i>Agregar Gasto
                    </button>
                </form>
            </div>

            <div>
                <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-lg p-6 text-white">
                    <h3 class="text-xl font-bold mb-4">Resumen del Mes</h3>
                    <div class="text-3xl font-bold mb-2">${{ number_format($totalMonth, 2) }}</div>
                    <p class="text-red-100">Total gastado en {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
                </div>

                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                    <h4 class="font-bold text-gray-800 mb-3">Por Categoría</h4>
                    <div class="space-y-2">
                        @foreach($categoryTotals as $cat => $total)
                            @php
                                $percentage = $totalMonth > 0 ? ($total / $totalMonth) * 100 : 0;
                                $categoryName = \App\Models\Expense::getCategories()[$cat] ?? $cat;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm">
                                    <span>{{ $categoryName }}</span>
                                    <span class="font-semibold">${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 flex flex-wrap gap-4">
            <form method="GET" action="{{ route('expenses.index') }}" class="flex gap-2">
                <select name="month" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    @forelse($months as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($m)->format('F Y') }}
                        </option>
                    @empty
                        <option value="{{ Carbon\Carbon::now()->format('Y-m') }}">
                            {{ Carbon\Carbon::now()->format('F Y') }}
                        </option>
                    @endforelse
                </select>

                <select name="category" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="all">Todas las categorías</option>
                    @foreach(\App\Models\Expense::getCategories() as $key => $label)
                        <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left">
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Categoría</th>
                        <th class="px-4 py-3">Monto</th>
                        <th class="px-4 py-3">Método</th>
                        <th class="px-4 py-3">Notas</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $expense->description }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-gray-200 rounded text-sm">{{ $expense->category_name }}</span>
                            </td>
                            <td class="px-4 py-3 font-bold text-red-600">${{ number_format($expense->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $expense->payment_method_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-sm">{{ $expense->notes ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <button onclick="editExpense({{ $expense->toJson() }})"
                                        class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar este gasto?')"
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                <i class="fas fa-wallet text-4xl mb-3"></i>
                                <p>No hay gastos registrados para este período</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <h2 class="text-xl font-bold mb-4">Editar Gasto</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                    <input type="text" name="description" id="editDescription" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Monto</label>
                    <input type="number" name="amount" id="editAmount" step="0.01" min="0.01" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                    <select name="category" id="editCategory" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        @foreach(\App\Models\Expense::getCategories() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                    <input type="date" name="expense_date" id="editDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Método de Pago</label>
                    <select name="payment_method" id="editPaymentMethod"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Seleccionar...</option>
                        @foreach(\App\Models\Expense::getPaymentMethods() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Notas</label>
                    <input type="text" name="notes" id="editNotes"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editExpense(expense) {
        document.getElementById('editDescription').value = expense.description;
        document.getElementById('editAmount').value = expense.amount;
        document.getElementById('editCategory').value = expense.category;
        document.getElementById('editDate').value = expense.expense_date.split('T')[0];
        document.getElementById('editPaymentMethod').value = expense.payment_method || '';
        document.getElementById('editNotes').value = expense.notes || '';
        document.getElementById('editForm').action = `/expenses/${expense.id}`;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endpush
