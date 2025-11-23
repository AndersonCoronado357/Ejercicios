<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $currentDate = Carbon::create($year, $month, 1);
        $upcomingEvents = Event::upcoming()->limit(5)->get();
        $todayEvents = Event::onDate(today())->get();

        return view('calendar.index', compact('currentDate', 'upcomingEvents', 'todayEvents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'color' => 'required|string',
            'all_day' => 'boolean',
            'reminder' => 'boolean',
            'reminder_minutes' => 'nullable|integer'
        ]);

        Event::create($validated);

        return redirect()->route('calendar.index')->with('success', 'Evento creado exitosamente');
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'color' => 'required|string',
            'all_day' => 'boolean',
            'reminder' => 'boolean',
            'reminder_minutes' => 'nullable|integer'
        ]);

        $event->update($validated);

        return redirect()->route('calendar.index')->with('success', 'Evento actualizado exitosamente');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('calendar.index')->with('success', 'Evento eliminado exitosamente');
    }

    public function getEvents(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $events = Event::inMonth($year, $month)->get();

        return response()->json($events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->format('Y-m-d\TH:i:s'),
                'end' => $event->end_date->format('Y-m-d\TH:i:s'),
                'color' => $event->color_class,
                'allDay' => $event->all_day,
                'description' => $event->description,
                'location' => $event->location
            ];
        }));
    }
}
