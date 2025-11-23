<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipCalculatorController extends Controller
{
    public function index()
    {
        return view('tip-calculator.index');
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'bill_amount' => 'required|numeric|min:0.01',
            'tip_percentage' => 'required|numeric|min:0|max:100',
            'people_count' => 'required|integer|min:1'
        ]);

        $billAmount = $validated['bill_amount'];
        $tipPercentage = $validated['tip_percentage'];
        $peopleCount = $validated['people_count'];

        $tipAmount = $billAmount * ($tipPercentage / 100);
        $totalAmount = $billAmount + $tipAmount;
        $perPerson = $totalAmount / $peopleCount;
        $tipPerPerson = $tipAmount / $peopleCount;

        $result = [
            'bill_amount' => number_format($billAmount, 2),
            'tip_percentage' => $tipPercentage,
            'tip_amount' => number_format($tipAmount, 2),
            'total_amount' => number_format($totalAmount, 2),
            'people_count' => $peopleCount,
            'per_person' => number_format($perPerson, 2),
            'tip_per_person' => number_format($tipPerPerson, 2)
        ];

        return response()->json($result);
    }
}
