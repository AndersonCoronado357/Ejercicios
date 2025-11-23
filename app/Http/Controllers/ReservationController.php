<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status', 'all');

        $query = Reservation::query();

        if ($date) {
            $query->whereDate('reservation_date', $date);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reservations = $query->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->get();

        $upcomingReservations = Reservation::where('reservation_date', '>=', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->limit(5)
            ->get();

        $todayCount = Reservation::whereDate('reservation_date', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $weekCount = Reservation::whereBetween('reservation_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        return view('reservations.index', compact('reservations', 'date', 'status', 'upcomingReservations', 'todayCount', 'weekCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'service_type' => 'required|string',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'notes' => 'nullable|string'
        ]);

        $serviceInfo = Reservation::getServiceTypes()[$validated['service_type']] ?? null;

        $validated['duration_minutes'] = $serviceInfo['duration'] ?? 60;
        $validated['price'] = $serviceInfo['price'] ?? 0;
        $validated['status'] = 'pending';

        $availableSlots = Reservation::getAvailableSlots($validated['reservation_date']);
        if (!in_array($validated['reservation_time'], $availableSlots)) {
            return back()->with('error', 'El horario seleccionado no está disponible');
        }

        Reservation::create($validated);

        return redirect()->route('reservations.index')->with('success', 'Reserva creada exitosamente');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'service_type' => 'required|string',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string'
        ]);

        $serviceInfo = Reservation::getServiceTypes()[$validated['service_type']] ?? null;
        $validated['duration_minutes'] = $serviceInfo['duration'] ?? 60;
        $validated['price'] = $serviceInfo['price'] ?? 0;

        $availableSlots = Reservation::getAvailableSlots($validated['reservation_date'], $reservation->id);
        if (!in_array($validated['reservation_time'], $availableSlots)) {
            return back()->with('error', 'El horario seleccionado no está disponible');
        }

        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Reserva actualizada exitosamente');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reserva eliminada exitosamente');
    }

    public function confirm(Reservation $reservation)
    {
        $reservation->update(['status' => 'confirmed']);
        return redirect()->route('reservations.index')->with('success', 'Reserva confirmada exitosamente');
    }
}
