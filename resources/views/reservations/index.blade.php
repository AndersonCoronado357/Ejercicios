@extends('layouts.app')

@section('title', 'Sistema de Reservas')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Sistema de Reservas</h1>

        <div class="grid lg:grid-cols-4 gap-6 mb-6">
            <div class="lg:col-span-3">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Nueva Reserva</h3>
                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Cliente</label>
                                <input type="text" name="customer_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                <input type="email" name="customer_email" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                                <input type="tel" name="customer_phone" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Servicio</label>
                                <select name="service_type" id="serviceType" required onchange="updateServiceInfo()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Seleccionar...</option>
                                    @foreach(\App\Models\Reservation::getServiceTypes() as $key => $service)
                                        <option value="{{ $key }}" data-duration="{{ $service['duration'] }}" data-price="{{ $service['price'] }}">
                                            {{ $service['name'] }} - ${{ $service['price'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                                <input type="date" name="reservation_date" id="reservationDate"
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ date('Y-m-d') }}"
                                       onchange="loadAvailableSlots()"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Hora</label>
                                <select name="reservation_time" id="reservationTime" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Seleccionar hora...</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Notas</label>
                            <textarea name="notes" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div id="serviceInfo" class="mb-4 p-4 bg-blue-50 rounded-lg hidden">
                            <p class="text-sm text-gray-700">
                                <i class="fas fa-clock mr-2"></i>Duración: <span id="serviceDuration">--</span> minutos
                            </p>
                            <p class="text-sm text-gray-700 mt-1">
                                <i class="fas fa-dollar-sign mr-2"></i>Precio: $<span id="servicePrice">--</span>
                            </p>
                        </div>

                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold">
                            <i class="fas fa-calendar-plus mr-2"></i>Crear Reserva
                        </button>
                    </form>
                </div>
            </div>

            <div>
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg p-6 text-white mb-4">
                    <h3 class="text-lg font-bold mb-4">Resumen</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-indigo-100 text-sm">Hoy</p>
                            <p class="text-3xl font-bold">{{ $todayCount }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-100 text-sm">Esta Semana</p>
                            <p class="text-3xl font-bold">{{ $weekCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-bold text-gray-800 mb-3">Próximas Citas</h4>
                    <div class="space-y-2">
                        @forelse($upcomingReservations as $upcoming)
                            <div class="text-sm p-2 bg-white rounded border-l-4 border-indigo-500">
                                <p class="font-semibold">{{ $upcoming->customer_name }}</p>
                                <p class="text-gray-600">{{ $upcoming->reservation_date->format('d/m') }} - {{ Carbon\Carbon::parse($upcoming->reservation_time)->format('H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $upcoming->service_name }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No hay citas próximas</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <form method="GET" action="{{ route('reservations.index') }}" class="flex gap-2">
                <input type="date" name="date" value="{{ $date }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Todos los estados</option>
                    @foreach(\App\Models\Reservation::getStatuses() as $key => $statusInfo)
                        <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>{{ $statusInfo['name'] }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left">
                        <th class="px-4 py-3">Hora</th>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Servicio</th>
                        <th class="px-4 py-3">Contacto</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Precio</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-semibold">{{ Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $reservation->reservation_date->format('d/m/Y') }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $reservation->customer_name }}</td>
                            <td class="px-4 py-3">
                                <span class="text-sm">{{ $reservation->service_name }}</span>
                                <span class="text-xs text-gray-500 block">{{ $reservation->duration_minutes }} min</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm">{{ $reservation->customer_email }}</p>
                                <p class="text-sm">{{ $reservation->customer_phone }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($reservation->status_color == 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($reservation->status_color == 'green') bg-green-100 text-green-800
                                    @elseif($reservation->status_color == 'red') bg-red-100 text-red-800
                                    @elseif($reservation->status_color == 'blue') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $reservation->status_name }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-bold">${{ number_format($reservation->price, 2) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @if($reservation->status == 'pending')
                                        <form action="{{ route('reservations.confirm', $reservation) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800" title="Confirmar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="editReservation({{ $reservation->toJson() }})"
                                            class="text-blue-600 hover:text-blue-800" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Eliminar esta reserva?')"
                                                class="text-red-600 hover:text-red-800" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                <p>No hay reservas para los criterios seleccionados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Editar Reserva</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Cliente</label>
                    <input type="text" name="customer_name" id="editCustomerName" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="customer_email" id="editCustomerEmail" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                    <input type="tel" name="customer_phone" id="editCustomerPhone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Servicio</label>
                    <select name="service_type" id="editServiceType" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach(\App\Models\Reservation::getServiceTypes() as $key => $service)
                            <option value="{{ $key }}">{{ $service['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                    <input type="date" name="reservation_date" id="editReservationDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Hora</label>
                    <input type="time" name="reservation_time" id="editReservationTime" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                    <select name="status" id="editStatus" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach(\App\Models\Reservation::getStatuses() as $key => $statusInfo)
                            <option value="{{ $key }}">{{ $statusInfo['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Notas</label>
                <textarea name="notes" id="editNotes" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateServiceInfo() {
        const select = document.getElementById('serviceType');
        const selectedOption = select.options[select.selectedIndex];
        const info = document.getElementById('serviceInfo');

        if (select.value) {
            const duration = selectedOption.getAttribute('data-duration');
            const price = selectedOption.getAttribute('data-price');

            document.getElementById('serviceDuration').textContent = duration;
            document.getElementById('servicePrice').textContent = price;
            info.classList.remove('hidden');
        } else {
            info.classList.add('hidden');
        }
    }

    function loadAvailableSlots() {
        const date = document.getElementById('reservationDate').value;
        const timeSelect = document.getElementById('reservationTime');

        if (!date) return;

        const reservations = @json($reservations);
        const occupiedSlots = reservations
            .filter(r => r.reservation_date === date && ['pending', 'confirmed'].includes(r.status))
            .map(r => r.reservation_time);

        timeSelect.innerHTML = '<option value="">Seleccionar hora...</option>';

        for (let hour = 9; hour < 18; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                if (!occupiedSlots.includes(time + ':00')) {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                }
            }
        }
    }

    function editReservation(reservation) {
        document.getElementById('editCustomerName').value = reservation.customer_name;
        document.getElementById('editCustomerEmail').value = reservation.customer_email;
        document.getElementById('editCustomerPhone').value = reservation.customer_phone;
        document.getElementById('editServiceType').value = reservation.service_type;
        document.getElementById('editReservationDate').value = reservation.reservation_date;
        document.getElementById('editReservationTime').value = reservation.reservation_time.substring(0, 5);
        document.getElementById('editStatus').value = reservation.status;
        document.getElementById('editNotes').value = reservation.notes || '';
        document.getElementById('editForm').action = `/reservations/${reservation.id}`;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    window.onload = function() {
        loadAvailableSlots();
    };
</script>
@endpush
