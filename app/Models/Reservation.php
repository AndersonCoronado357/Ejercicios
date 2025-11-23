<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'service_type',
        'reservation_date',
        'reservation_time',
        'duration_minutes',
        'status',
        'notes',
        'price'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime:H:i',
        'price' => 'decimal:2'
    ];

    public static function getServiceTypes()
    {
        return [
            'consultation' => ['name' => 'Consulta General', 'duration' => 30, 'price' => 50],
            'haircut' => ['name' => 'Corte de Cabello', 'duration' => 45, 'price' => 35],
            'massage' => ['name' => 'Masaje Relajante', 'duration' => 60, 'price' => 80],
            'dental' => ['name' => 'Consulta Dental', 'duration' => 45, 'price' => 75],
            'therapy' => ['name' => 'Sesión de Terapia', 'duration' => 50, 'price' => 100],
            'manicure' => ['name' => 'Manicure', 'duration' => 40, 'price' => 40],
            'pedicure' => ['name' => 'Pedicure', 'duration' => 50, 'price' => 45],
            'facial' => ['name' => 'Tratamiento Facial', 'duration' => 60, 'price' => 90],
            'medical' => ['name' => 'Consulta Médica', 'duration' => 30, 'price' => 120],
            'other' => ['name' => 'Otro Servicio', 'duration' => 60, 'price' => 0]
        ];
    }

    public static function getStatuses()
    {
        return [
            'pending' => ['name' => 'Pendiente', 'color' => 'yellow'],
            'confirmed' => ['name' => 'Confirmada', 'color' => 'green'],
            'cancelled' => ['name' => 'Cancelada', 'color' => 'red'],
            'completed' => ['name' => 'Completada', 'color' => 'blue']
        ];
    }

    public function getServiceNameAttribute()
    {
        return self::getServiceTypes()[$this->service_type]['name'] ?? $this->service_type;
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status]['name'] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return self::getStatuses()[$this->status]['color'] ?? 'gray';
    }

    public function getEndTimeAttribute()
    {
        $time = Carbon::parse($this->reservation_date->format('Y-m-d') . ' ' . $this->reservation_time);
        return $time->addMinutes($this->duration_minutes)->format('H:i');
    }

    public static function getAvailableSlots($date, $excludeId = null)
    {
        $slots = [];
        $startHour = 9;
        $endHour = 18;
        $slotDuration = 30;

        $existingReservations = self::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($excludeId, function($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->get();

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += $slotDuration) {
                $time = sprintf('%02d:%02d', $hour, $minute);
                $slotStart = Carbon::parse($date . ' ' . $time);
                $slotEnd = $slotStart->copy()->addMinutes($slotDuration);

                $isAvailable = true;
                foreach ($existingReservations as $reservation) {
                    $resStart = Carbon::parse($date . ' ' . $reservation->reservation_time);
                    $resEnd = $resStart->copy()->addMinutes($reservation->duration_minutes);

                    if (!($slotEnd <= $resStart || $slotStart >= $resEnd)) {
                        $isAvailable = false;
                        break;
                    }
                }

                if ($isAvailable) {
                    $slots[] = $time;
                }
            }
        }

        return $slots;
    }
}
