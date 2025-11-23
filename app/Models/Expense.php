<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'category',
        'expense_date',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date'
    ];

    public static function getCategories()
    {
        return [
            'food' => 'Alimentación',
            'transport' => 'Transporte',
            'entertainment' => 'Entretenimiento',
            'health' => 'Salud',
            'education' => 'Educación',
            'shopping' => 'Compras',
            'bills' => 'Servicios',
            'rent' => 'Alquiler',
            'savings' => 'Ahorros',
            'other' => 'Otros'
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Efectivo',
            'credit_card' => 'Tarjeta de Crédito',
            'debit_card' => 'Tarjeta de Débito',
            'transfer' => 'Transferencia',
            'other' => 'Otro'
        ];
    }

    public function getCategoryNameAttribute()
    {
        return self::getCategories()[$this->category] ?? $this->category;
    }

    public function getPaymentMethodNameAttribute()
    {
        return self::getPaymentMethods()[$this->payment_method] ?? $this->payment_method;
    }
}
