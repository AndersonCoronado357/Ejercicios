@extends('layouts.app')

@section('title', 'Resumen de Gastos')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Resumen Anual - {{ $year }}</h1>
            <a href="{{ route('expenses.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <div class="mb-6 flex gap-2">
            @foreach($availableYears as $y)
                <a href="{{ route('expenses.summary', ['year' => $y]) }}"
                   class="px-4 py-2 {{ $y == $year ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:bg-red-700 hover:text-white transition">
                    {{ $y }}
                </a>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-2">Total del Año</h3>
                <div class="text-4xl font-bold">${{ number_format($yearTotal, 2) }}</div>
                <p class="text-red-100 mt-2">Promedio mensual: ${{ number_format($yearTotal / 12, 2) }}</p>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-2">Mes con Mayor Gasto</h3>
                @php
                    $monthlyTotals = $monthlyData->groupBy('month')->map(function($items) {
                        return $items->sum('total');
                    });
                    $maxMonth = $monthlyTotals->count() > 0 ? $monthlyTotals->sortDesc()->keys()->first() : null;
                    $maxAmount = $maxMonth ? $monthlyTotals[$maxMonth] : 0;
                @endphp
                <div class="text-4xl font-bold">${{ number_format($maxAmount, 2) }}</div>
                <p class="text-blue-100 mt-2">{{ $maxMonth ? \Carbon\Carbon::create()->month($maxMonth)->format('F') : 'N/A' }}</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-teal-600 rounded-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-2">Categoría Principal</h3>
                @php
                    $topCategory = $categoryYearTotals->sortByDesc('total')->first();
                @endphp
                <div class="text-4xl font-bold">
                    ${{ $topCategory ? number_format($topCategory->total, 2) : '0.00' }}
                </div>
                <p class="text-green-100 mt-2">
                    {{ $topCategory ? (\App\Models\Expense::getCategories()[$topCategory->category] ?? $topCategory->category) : 'N/A' }}
                </p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Gastos por Mes</h3>
                <canvas id="monthlyChart"></canvas>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Distribución por Categoría</h3>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Top 10 Gastos Más Altos</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-left">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Categoría</th>
                            <th class="px-4 py-3">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topExpenses as $index => $expense)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $expense->expense_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $expense->description }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-gray-300 rounded text-sm">{{ $expense->category_name }}</span>
                                </td>
                                <td class="px-4 py-3 font-bold text-red-600">${{ number_format($expense->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyData = @json($monthlyData->groupBy('month')->map(function($items) {
        return $items->sum('total');
    }));

    const monthlyChartCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyChartCtx, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Gastos',
                data: [
                    monthlyData[1] || 0, monthlyData[2] || 0, monthlyData[3] || 0,
                    monthlyData[4] || 0, monthlyData[5] || 0, monthlyData[6] || 0,
                    monthlyData[7] || 0, monthlyData[8] || 0, monthlyData[9] || 0,
                    monthlyData[10] || 0, monthlyData[11] || 0, monthlyData[12] || 0
                ],
                backgroundColor: 'rgba(239, 68, 68, 0.5)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    const categoryData = @json($categoryYearTotals);
    const categories = @json(\App\Models\Expense::getCategories());

    const categoryChartCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryChartCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => categories[item.category] || item.category),
            datasets: [{
                data: categoryData.map(item => item.total),
                backgroundColor: [
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(251, 191, 36, 0.7)',
                    'rgba(168, 85, 247, 0.7)',
                    'rgba(236, 72, 153, 0.7)',
                    'rgba(20, 184, 166, 0.7)',
                    'rgba(251, 146, 60, 0.7)',
                    'rgba(100, 116, 139, 0.7)',
                    'rgba(165, 180, 252, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': $' + context.parsed.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
