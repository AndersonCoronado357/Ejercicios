<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $category = $request->get('category', 'all');

        $query = Expense::query();

        if ($month) {
            $query->whereYear('expense_date', Carbon::parse($month)->year)
                  ->whereMonth('expense_date', Carbon::parse($month)->month);
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        $totalMonth = $expenses->sum('amount');

        $categoryTotals = $expenses->groupBy('category')->map(function ($group) {
            return $group->sum('amount');
        });

        $months = Expense::selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        return view('expenses.index', compact('expenses', 'totalMonth', 'categoryTotals', 'month', 'category', 'months'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Gasto registrado exitosamente');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Gasto actualizado exitosamente');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Gasto eliminado exitosamente');
    }

    public function summary(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $monthlyData = Expense::selectRaw('
                MONTH(expense_date) as month,
                category,
                SUM(amount) as total
            ')
            ->whereYear('expense_date', $year)
            ->groupBy('month', 'category')
            ->get();

        $yearTotal = Expense::whereYear('expense_date', $year)->sum('amount');

        $categoryYearTotals = Expense::whereYear('expense_date', $year)
            ->groupBy('category')
            ->selectRaw('category, SUM(amount) as total')
            ->get();

        $topExpenses = Expense::whereYear('expense_date', $year)
            ->orderBy('amount', 'desc')
            ->limit(10)
            ->get();

        $availableYears = Expense::selectRaw('YEAR(expense_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('expenses.summary', compact('monthlyData', 'year', 'yearTotal', 'categoryYearTotals', 'topExpenses', 'availableYears'));
    }
}
