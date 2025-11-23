<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordGeneratorController extends Controller
{
    public function index()
    {
        return view('password-generator.index');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'length' => 'required|integer|min:4|max:128',
            'uppercase' => 'boolean',
            'lowercase' => 'boolean',
            'numbers' => 'boolean',
            'symbols' => 'boolean',
            'exclude_similar' => 'boolean',
            'exclude_ambiguous' => 'boolean'
        ]);

        $length = $validated['length'];
        $characters = '';

        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        if ($validated['exclude_similar'] ?? false) {
            $uppercase = str_replace(['I', 'O'], '', $uppercase);
            $lowercase = str_replace(['l', 'o'], '', $lowercase);
            $numbers = str_replace(['0', '1'], '', $numbers);
        }

        if ($validated['exclude_ambiguous'] ?? false) {
            $symbols = '!@#$%^&*';
        }

        $requiredChars = [];

        if ($validated['uppercase'] ?? false) {
            $characters .= $uppercase;
            $requiredChars[] = $uppercase[random_int(0, strlen($uppercase) - 1)];
        }

        if ($validated['lowercase'] ?? false) {
            $characters .= $lowercase;
            $requiredChars[] = $lowercase[random_int(0, strlen($lowercase) - 1)];
        }

        if ($validated['numbers'] ?? false) {
            $characters .= $numbers;
            $requiredChars[] = $numbers[random_int(0, strlen($numbers) - 1)];
        }

        if ($validated['symbols'] ?? false) {
            $characters .= $symbols;
            $requiredChars[] = $symbols[random_int(0, strlen($symbols) - 1)];
        }

        if (empty($characters)) {
            return response()->json([
                'error' => 'Debe seleccionar al menos un tipo de carácter'
            ], 422);
        }

        $password = '';
        $remainingLength = $length - count($requiredChars);

        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        $password .= implode('', $requiredChars);
        $password = str_shuffle($password);

        $strength = $this->calculateStrength($password, $validated);

        return response()->json([
            'password' => $password,
            'strength' => $strength,
            'entropy' => $this->calculateEntropy($password, $characters)
        ]);
    }

    private function calculateStrength($password, $options)
    {
        $score = 0;
        $length = strlen($password);

        if ($length >= 8) $score += 20;
        if ($length >= 12) $score += 20;
        if ($length >= 16) $score += 20;

        if ($options['uppercase'] ?? false) $score += 10;
        if ($options['lowercase'] ?? false) $score += 10;
        if ($options['numbers'] ?? false) $score += 10;
        if ($options['symbols'] ?? false) $score += 10;

        if ($score <= 30) return ['level' => 'weak', 'text' => 'Débil', 'color' => 'red'];
        if ($score <= 50) return ['level' => 'fair', 'text' => 'Regular', 'color' => 'orange'];
        if ($score <= 70) return ['level' => 'good', 'text' => 'Buena', 'color' => 'yellow'];
        if ($score <= 90) return ['level' => 'strong', 'text' => 'Fuerte', 'color' => 'green'];

        return ['level' => 'very-strong', 'text' => 'Muy Fuerte', 'color' => 'green'];
    }

    private function calculateEntropy($password, $characterSet)
    {
        $poolSize = strlen($characterSet);
        $entropy = strlen($password) * log($poolSize, 2);
        return round($entropy, 2);
    }
}
