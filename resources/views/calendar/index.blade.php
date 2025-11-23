@extends('layouts.app')

@section('title', 'Calendario de Eventos')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Calendario de Eventos</h1>
        <button onclick="openCreateModal()" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-bold">
            <i class="fas fa-plus mr-2"></i>Nuevo Evento
        </button>
    </div>

    <div class="grid lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <button onclick="previousMonth()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800">
                        <span id="currentMonth">{{ $currentDate->format('F Y') }}</span>
                    </h2>
                    <button onclick="nextMonth()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="grid grid-cols-7 gap-2 mb-2">
                    <div class="text-center font-bold text-gray-600 py-2">Dom</div>
                    <div class="text-center font-bold text-gray-600 py-2">Lun</div>
                    <div class="text-center font-bold text-gray-600 py-2">Mar</div>
                    <div class="text-center font-bold text-gray-600 py-2">Mié</div>
                    <div class="text-center font-bold text-gray-600 py-2">Jue</div>
                    <div class="text-center font-bold text-gray-600 py-2">Vie</div>
                    <div class="text-center font-bold text-gray-600 py-2">Sáb</div>
                </div>

                <div id="calendarGrid" class="grid grid-cols-7 gap-2">
                </div>
            </div>
        </div>

        <div>
            <div class="bg-gradient-to-br from-teal-500 to-blue-600 rounded-lg p-6 text-white mb-4">
                <h3 class="text-lg font-bold mb-4">
                    <i class="fas fa-calendar-day mr-2"></i>Hoy
                </h3>
                <p class="text-3xl font-bold">{{ date('d') }}</p>
                <p class="text-sm">{{ date('F Y') }}</p>
                <div class="mt-4 pt-4 border-t border-teal-300">
                    <p class="text-sm">{{ $todayEvents->count() }} eventos</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-4">
                <h3 class="font-bold text-gray-800 mb-4">
                    <i class="fas fa-clock mr-2"></i>Próximos Eventos
                </h3>
                <div class="space-y-3">
                    @forelse($upcomingEvents as $event)
                        <div class="p-3 bg-gray-50 rounded border-l-4 {{ str_replace('bg-', 'border-', $event->color_class) }} cursor-pointer hover:bg-gray-100"
                             onclick="viewEvent({{ $event->toJson() }})">
                            <p class="font-semibold text-sm">{{ $event->title }}</p>
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-calendar mr-1"></i>{{ $event->start_date->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-clock mr-1"></i>{{ $event->start_date->format('H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No hay eventos próximos</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-4 mt-4">
                <h3 class="font-bold text-gray-800 mb-3">
                    <i class="fas fa-palette mr-2"></i>Leyenda
                </h3>
                <div class="space-y-2">
                    @foreach(\App\Models\Event::getColors() as $key => $color)
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 rounded {{ $color['class'] }} mr-2"></div>
                            <span class="text-gray-600">{{ $color['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Nuevo Evento</h2>
        <form action="{{ route('calendar.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Título del Evento</label>
                <input type="text" name="title" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                <textarea name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha de Inicio</label>
                    <input type="datetime-local" name="start_date" id="startDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha de Fin</label>
                    <input type="datetime-local" name="end_date" id="endDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ubicación</label>
                <input type="text" name="location"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                <select name="color" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach(\App\Models\Event::getColors() as $key => $color)
                        <option value="{{ $key }}">{{ $color['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="all_day" value="1" onchange="toggleAllDay(this)"
                           class="mr-3 w-4 h-4 text-teal-600">
                    <span class="text-gray-700 font-semibold">Evento de todo el día</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="reminder" value="1" id="reminderCheckbox" onchange="toggleReminder(this)"
                           class="mr-3 w-4 h-4 text-teal-600">
                    <span class="text-gray-700 font-semibold">Activar recordatorio</span>
                </label>
            </div>

            <div id="reminderOptions" class="mb-4 hidden">
                <label class="block text-gray-700 text-sm font-bold mb-2">Recordar</label>
                <select name="reminder_minutes"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach(\App\Models\Event::getReminderOptions() as $minutes => $label)
                        <option value="{{ $minutes }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeCreateModal()"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">
                    Crear Evento
                </button>
            </div>
        </form>
    </div>
</div>

<div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-gray-800" id="viewTitle"></h2>
            <button onclick="closeViewModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="space-y-3">
            <div id="viewDescription" class="text-gray-600"></div>

            <div class="flex items-center text-gray-700">
                <i class="fas fa-calendar w-6"></i>
                <span id="viewStartDate"></span>
            </div>

            <div class="flex items-center text-gray-700">
                <i class="fas fa-clock w-6"></i>
                <span id="viewDuration"></span>
            </div>

            <div id="viewLocationDiv" class="flex items-center text-gray-700 hidden">
                <i class="fas fa-map-marker-alt w-6"></i>
                <span id="viewLocation"></span>
            </div>

            <div id="viewReminderDiv" class="flex items-center text-gray-700 hidden">
                <i class="fas fa-bell w-6"></i>
                <span id="viewReminder"></span>
            </div>
        </div>

        <div class="flex gap-2 mt-6">
            <button onclick="editEvent()" class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </button>
            <button onclick="deleteEvent()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                <i class="fas fa-trash mr-2"></i>Eliminar
            </button>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Editar Evento</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Título del Evento</label>
                <input type="text" name="title" id="editTitle" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                <textarea name="description" id="editDescription" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha de Inicio</label>
                    <input type="datetime-local" name="start_date" id="editStartDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha de Fin</label>
                    <input type="datetime-local" name="end_date" id="editEndDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ubicación</label>
                <input type="text" name="location" id="editLocation"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                <select name="color" id="editColor" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach(\App\Models\Event::getColors() as $key => $color)
                        <option value="{{ $key }}">{{ $color['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="all_day" value="1" id="editAllDay" onchange="toggleAllDay(this)"
                           class="mr-3 w-4 h-4 text-teal-600">
                    <span class="text-gray-700 font-semibold">Evento de todo el día</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="reminder" value="1" id="editReminderCheckbox" onchange="toggleEditReminder(this)"
                           class="mr-3 w-4 h-4 text-teal-600">
                    <span class="text-gray-700 font-semibold">Activar recordatorio</span>
                </label>
            </div>

            <div id="editReminderOptions" class="mb-4 hidden">
                <label class="block text-gray-700 text-sm font-bold mb-2">Recordar</label>
                <select name="reminder_minutes" id="editReminderMinutes"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach(\App\Models\Event::getReminderOptions() as $minutes => $label)
                        <option value="{{ $minutes }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentYear = {{ $currentDate->year }};
    let currentMonth = {{ $currentDate->month }};
    let currentEvent = null;
    let events = [];

    function loadEvents() {
        fetch(`{{ route('calendar.events') }}?year=${currentYear}&month=${currentMonth}`)
            .then(response => response.json())
            .then(data => {
                events = data;
                renderCalendar();
            });
    }

    function renderCalendar() {
        const firstDay = new Date(currentYear, currentMonth - 1, 1);
        const lastDay = new Date(currentYear, currentMonth, 0);
        const daysInMonth = lastDay.getDate();
        const startDay = firstDay.getDay();

        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        document.getElementById('currentMonth').textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '';

        for (let i = 0; i < startDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'h-24 bg-gray-50 rounded';
            grid.appendChild(emptyDay);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.className = 'h-24 bg-white border rounded p-2 hover:bg-gray-50 cursor-pointer relative overflow-hidden';

            const isToday = day === new Date().getDate() &&
                          currentMonth === (new Date().getMonth() + 1) &&
                          currentYear === new Date().getFullYear();

            if (isToday) {
                dayCell.classList.add('border-teal-500', 'border-2');
            }

            const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayEvents = events.filter(e => {
                const eventDate = e.start.split('T')[0];
                return eventDate === dateStr;
            });

            dayCell.innerHTML = `
                <div class="font-bold text-sm ${isToday ? 'text-teal-600' : 'text-gray-700'}">${day}</div>
                <div class="mt-1 space-y-1">
                    ${dayEvents.slice(0, 2).map(e => `
                        <div class="${e.color} text-white text-xs p-1 rounded truncate" onclick="event.stopPropagation(); viewEventById(${e.id})">
                            ${e.title}
                        </div>
                    `).join('')}
                    ${dayEvents.length > 2 ? `<div class="text-xs text-gray-500">+${dayEvents.length - 2} más</div>` : ''}
                </div>
            `;

            dayCell.onclick = () => {
                document.getElementById('startDate').value = `${dateStr}T09:00`;
                document.getElementById('endDate').value = `${dateStr}T10:00`;
                openCreateModal();
            };

            grid.appendChild(dayCell);
        }
    }

    function previousMonth() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        loadEvents();
    }

    function nextMonth() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        loadEvents();
    }

    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
    }

    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }

    function toggleAllDay(checkbox) {
        const startDate = checkbox.form.querySelector('[name="start_date"]');
        const endDate = checkbox.form.querySelector('[name="end_date"]');

        if (checkbox.checked) {
            startDate.type = 'date';
            endDate.type = 'date';
        } else {
            startDate.type = 'datetime-local';
            endDate.type = 'datetime-local';
        }
    }

    function toggleReminder(checkbox) {
        document.getElementById('reminderOptions').classList.toggle('hidden', !checkbox.checked);
    }

    function toggleEditReminder(checkbox) {
        document.getElementById('editReminderOptions').classList.toggle('hidden', !checkbox.checked);
    }

    function viewEventById(id) {
        const event = events.find(e => e.id === id);
        if (event) {
            currentEvent = event;
            viewEvent(event);
        }
    }

    function viewEvent(event) {
        currentEvent = event;
        document.getElementById('viewTitle').textContent = event.title;
        document.getElementById('viewDescription').textContent = event.description || 'Sin descripción';

        const startDate = new Date(event.start_date || event.start);
        document.getElementById('viewStartDate').textContent = startDate.toLocaleString('es-ES', {
            dateStyle: 'full',
            timeStyle: event.all_day || event.allDay ? undefined : 'short'
        });

        const endDate = new Date(event.end_date || event.end);
        const duration = (endDate - startDate) / 1000 / 60;
        let durationText = '';
        if (duration < 60) {
            durationText = `${duration} minutos`;
        } else if (duration < 1440) {
            durationText = `${Math.floor(duration / 60)} horas`;
        } else {
            durationText = `${Math.floor(duration / 1440)} días`;
        }
        document.getElementById('viewDuration').textContent = durationText;

        if (event.location) {
            document.getElementById('viewLocation').textContent = event.location;
            document.getElementById('viewLocationDiv').classList.remove('hidden');
        } else {
            document.getElementById('viewLocationDiv').classList.add('hidden');
        }

        if (event.reminder) {
            const reminderOptions = @json(\App\Models\Event::getReminderOptions());
            document.getElementById('viewReminder').textContent = reminderOptions[event.reminder_minutes] || 'Activado';
            document.getElementById('viewReminderDiv').classList.remove('hidden');
        } else {
            document.getElementById('viewReminderDiv').classList.add('hidden');
        }

        document.getElementById('viewModal').style.display = 'flex';
    }

    function closeViewModal() {
        document.getElementById('viewModal').style.display = 'none';
    }

    function editEvent() {
        closeViewModal();

        document.getElementById('editTitle').value = currentEvent.title;
        document.getElementById('editDescription').value = currentEvent.description || '';

        const startDate = currentEvent.start_date || currentEvent.start;
        const endDate = currentEvent.end_date || currentEvent.end;

        document.getElementById('editStartDate').value = startDate.substring(0, 16);
        document.getElementById('editEndDate').value = endDate.substring(0, 16);
        document.getElementById('editLocation').value = currentEvent.location || '';
        document.getElementById('editColor').value = currentEvent.color;
        document.getElementById('editAllDay').checked = currentEvent.all_day || currentEvent.allDay;
        document.getElementById('editReminderCheckbox').checked = currentEvent.reminder;

        if (currentEvent.reminder) {
            document.getElementById('editReminderMinutes').value = currentEvent.reminder_minutes;
            document.getElementById('editReminderOptions').classList.remove('hidden');
        }

        document.getElementById('editForm').action = `/calendar/events/${currentEvent.id}`;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function deleteEvent() {
        if (confirm('¿Estás seguro de eliminar este evento?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/calendar/events/${currentEvent.id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    window.onload = function() {
        loadEvents();
    };
</script>
@endpush
