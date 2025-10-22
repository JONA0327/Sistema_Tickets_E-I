<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Configuración de Mantenimientos - Panel Administrativo</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
        <header class="bg-white shadow-sm border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Configuración de Mantenimientos</h1>
                            <p class="text-sm text-gray-600">Gestiona horarios y disponibilidad de mantenimientos</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Tickets</a>
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Panel Admin</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Horarios de mantenimiento</h2>
                    <p class="text-gray-600">Define los días y horarios en los que se pueden agendar mantenimientos.</p>
                </div>
                <a href="{{ route('admin.maintenance.computers.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-green-300 bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                    Expedientes de equipos
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white border border-blue-100 rounded-xl shadow-sm p-6 mb-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Agregar horarios en lote</h3>
                <p class="text-sm text-gray-600 mb-6">Define un rango de fechas y horarios. El sistema dividirá automáticamente el tiempo según la capacidad especificada.</p>
                
                <form method="POST" action="{{ route('admin.maintenance.slots.store-bulk') }}" id="bulkScheduleForm">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Columna izquierda: Fechas -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-800">Rango de fechas</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio <span class="text-red-500">*</span></label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @error('start_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha fin <span class="text-red-500">*</span></label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @error('end_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            
                            <!-- Días de la semana -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Días de aplicación</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="days[]" value="monday" class="rounded border-gray-300 text-blue-600 mr-2" {{ in_array('monday', old('days', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">Lunes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="days[]" value="tuesday" class="rounded border-gray-300 text-blue-600 mr-2" {{ in_array('tuesday', old('days', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">Martes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="days[]" value="wednesday" class="rounded border-gray-300 text-blue-600 mr-2" {{ in_array('wednesday', old('days', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">Miércoles</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="days[]" value="thursday" class="rounded border-gray-300 text-blue-600 mr-2" {{ in_array('thursday', old('days', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">Jueves</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="days[]" value="friday" class="rounded border-gray-300 text-blue-600 mr-2" {{ in_array('friday', old('days', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">Viernes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="days[]" value="saturday" class="rounded border-gray-300 text-blue-600 mr-2" {{ in_array('saturday', old('days', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">Sábado</span>
                                    </label>
                                </div>
                                @error('days')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Columna derecha: Horarios y Capacidad -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-800">Configuración de horarios</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="bulk_start_time" class="block text-sm font-medium text-gray-700 mb-1">Hora inicio <span class="text-red-500">*</span></label>
                                    <input type="time" id="bulk_start_time" name="bulk_start_time" value="{{ old('bulk_start_time', '09:00') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @error('bulk_start_time')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="bulk_end_time" class="block text-sm font-medium text-gray-700 mb-1">Hora fin <span class="text-red-500">*</span></label>
                                    <input type="time" id="bulk_end_time" name="bulk_end_time" value="{{ old('bulk_end_time', '13:00') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @error('bulk_end_time')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="total_capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacidad total <span class="text-red-500">*</span></label>
                                <input type="number" min="1" max="20" id="total_capacity" name="total_capacity" value="{{ old('total_capacity', 4) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateSlots()" required>
                                <p class="text-xs text-gray-500 mt-1">Número de slots en los que se dividirá el tiempo total</p>
                                @error('total_capacity')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Vista previa de slots -->
                            <div id="slotsPreview" class="bg-gray-50 rounded-lg p-4">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Vista previa de horarios</h5>
                                <div id="slotsContainer" class="space-y-1 text-xs text-gray-600">
                                    <!-- Se llena con JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span id="totalDays">0</span> días seleccionados × <span id="totalSlots">0</span> horarios = <span id="totalSchedules" class="font-medium">0</span> horarios totales
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Crear horarios en lote
                        </button>
                    </div>
                </form>
            </div>

            <!-- Formulario individual (simplificado) -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-10">
                <details class="group">
                    <summary class="cursor-pointer list-none">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-700">Agregar horario individual</h3>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </summary>
                    <div class="mt-4">
                        <form method="POST" action="{{ route('admin.maintenance.slots.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            @csrf
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
                                <input type="date" id="date" name="date" value="{{ old('date') }}" min="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Hora de inicio <span class="text-red-500">*</span></label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('start_time')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Hora de fin <span class="text-red-500">*</span></label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('end_time')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacidad <span class="text-red-500">*</span></label>
                                <input type="number" min="1" max="10" id="capacity" name="capacity" value="{{ old('capacity', 1) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('capacity')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Guardar horario
                                </button>
                            </div>
                        </form>
                    </div>
                </details>
            </div>

            <div class="space-y-6">
                @forelse($groupedSlots as $date => $slots)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($date)->translatedFormat('d \d\e F, Y') }}</h4>
                                <p class="text-sm text-gray-500">{{ $slots->count() }} horario(s) configurado(s)</p>
                            </div>
                            <div class="text-sm text-gray-600">
                                Capacidad total: <span class="font-semibold text-blue-600">{{ $slots->sum('capacity') }}</span>
                                · Reservados: <span class="font-semibold text-yellow-600">{{ $slots->sum('booked_count') }}</span>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($slots as $slot)
                                <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm font-semibold text-blue-700">
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <div>Capacidad: <span class="font-semibold text-gray-900">{{ $slot->capacity }}</span></div>
                                            <div>Reservados: <span class="font-semibold text-gray-900">{{ $slot->booked_count }}</span></div>
                                            <div>Estado: <span class="font-semibold {{ $slot->is_active ? 'text-green-600' : 'text-gray-500' }}">{{ $slot->is_active ? 'Activo' : 'Inactivo' }}</span></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <form method="POST" action="{{ route('admin.maintenance.slots.update', $slot) }}" class="flex items-center space-x-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" min="1" max="20" name="capacity" value="{{ $slot->capacity }}" class="w-20 border border-gray-300 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <label class="flex items-center text-xs text-gray-600">
                                                <input type="checkbox" name="is_active" value="1" class="mr-2 rounded border-gray-300 text-green-600 focus:ring-green-500" {{ $slot->is_active ? 'checked' : '' }}>
                                                Activo
                                            </label>
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">Actualizar</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.maintenance.slots.destroy', $slot) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este horario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-gray-200 rounded-xl p-6 text-center text-gray-600">
                        <p>No hay horarios configurados. ¡Comienza agregando uno!</p>
                    </div>
                @endforelse
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                calculateSlots();
                updateTotalDays();
                
                // Event listeners
                document.getElementById('bulk_start_time').addEventListener('change', calculateSlots);
                document.getElementById('bulk_end_time').addEventListener('change', calculateSlots);
                document.getElementById('total_capacity').addEventListener('change', calculateSlots);
                
                // Event listeners para los checkboxes de días
                document.querySelectorAll('input[name="days[]"]').forEach(checkbox => {
                    checkbox.addEventListener('change', updateTotalDays);
                });
                
                // Event listeners para las fechas
                document.getElementById('start_date').addEventListener('change', function() {
                    updateTotalDays();
                    updateEndDateMin();
                });
                document.getElementById('end_date').addEventListener('change', updateTotalDays);
            });

            function updateEndDateMin() {
                const startDate = document.getElementById('start_date').value;
                const endDateInput = document.getElementById('end_date');
                
                if (startDate) {
                    endDateInput.min = startDate;
                    // Si la fecha fin es anterior a la fecha inicio, limpiarla
                    if (endDateInput.value && endDateInput.value < startDate) {
                        endDateInput.value = '';
                    }
                } else {
                    // Si no hay fecha inicio, usar la fecha actual como mínimo
                    endDateInput.min = '{{ date('Y-m-d') }}';
                }
            }

            function calculateSlots() {
                const startTime = document.getElementById('bulk_start_time').value;
                const endTime = document.getElementById('bulk_end_time').value;
                const capacity = parseInt(document.getElementById('total_capacity').value);
                const container = document.getElementById('slotsContainer');
                
                if (!startTime || !endTime || !capacity) {
                    container.innerHTML = '<p class="text-gray-500">Completa los campos para ver la vista previa</p>';
                    return;
                }
                
                const start = new Date(`2000-01-01T${startTime}:00`);
                const end = new Date(`2000-01-01T${endTime}:00`);
                
                if (end <= start) {
                    container.innerHTML = '<p class="text-red-500">La hora de fin debe ser posterior a la hora de inicio</p>';
                    return;
                }
                
                // Calcular duración total en minutos
                const totalMinutes = (end - start) / (1000 * 60);
                
                // Calcular duración exacta por slot
                let slotDuration = Math.floor(totalMinutes / capacity);
                
                // Verificar duración mínima
                if (slotDuration < 15) {
                    const maxCapacity = Math.floor(totalMinutes / 15);
                    container.innerHTML = `<p class="text-amber-600">⚠️ Tiempo insuficiente. Máximo ${maxCapacity} slots de 15 minutos cada uno</p>`;
                    return;
                }
                
                // Redondear solo si es necesario para mejor distribución
                if (slotDuration >= 15 && slotDuration < 30) {
                    // Mantener duración exacta para slots pequeños
                } else if (slotDuration >= 30 && slotDuration < 60) {
                    // Redondear a múltiplos de 15 para mejor legibilidad
                    slotDuration = Math.floor(slotDuration / 15) * 15;
                } else {
                    // Para slots de 1h+, redondear a múltiplos de 30
                    slotDuration = Math.floor(slotDuration / 30) * 30;
                }
                
                // Generar slots
                let html = '';
                let currentTime = new Date(start);
                let actualSlotsCreated = 0;
                
                for (let i = 0; i < capacity; i++) {
                    const slotStart = new Date(currentTime);
                    const slotEnd = new Date(currentTime.getTime() + (slotDuration * 60 * 1000));
                    
                    // Verificar que no exceda la hora de fin
                    if (slotEnd > end) {
                        break; // No crear más slots si excede el tiempo
                    }
                    
                    html += `<div class="flex justify-between items-center py-1 px-2 bg-white rounded border">
                        <span class="font-medium">Slot ${i + 1}:</span>
                        <span class="text-blue-600">${formatTime(slotStart)} - ${formatTime(slotEnd)} (${formatMinutes(slotDuration)})</span>
                    </div>`;
                    
                    currentTime.setMinutes(currentTime.getMinutes() + slotDuration);
                    actualSlotsCreated++;
                }
                
                // Mostrar información adicional
                if (actualSlotsCreated < capacity) {
                    html += `<div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-yellow-800 text-xs">
                        ⚠️ Solo se pueden crear ${actualSlotsCreated} de ${capacity} slots solicitados debido al tiempo disponible.
                    </div>`;
                }
                
                // Información de debug
                html += `<div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-blue-800 text-xs">
                    📊 Tiempo total: ${totalMinutes} min | Duración por slot: ${slotDuration} min | Slots creados: ${actualSlotsCreated}
                </div>`;
                
                container.innerHTML = html;
                document.getElementById('totalSlots').textContent = actualSlotsCreated;
                updateTotalSchedules();
            }
            
            function updateTotalDays() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const selectedDays = Array.from(document.querySelectorAll('input[name="days[]"]:checked')).map(cb => cb.value);
                
                if (!startDate || !endDate || selectedDays.length === 0) {
                    document.getElementById('totalDays').textContent = '0';
                    updateTotalSchedules();
                    return;
                }
                
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end < start) {
                    document.getElementById('totalDays').textContent = '0';
                    updateTotalSchedules();
                    return;
                }
                
                // Mapeo de días
                const dayMap = {
                    'monday': 1, 'tuesday': 2, 'wednesday': 3, 'thursday': 4, 
                    'friday': 5, 'saturday': 6, 'sunday': 0
                };
                
                let totalDays = 0;
                const currentDate = new Date(start);
                
                while (currentDate <= end) {
                    const dayOfWeek = currentDate.getDay();
                    
                    for (const selectedDay of selectedDays) {
                        if (dayMap[selectedDay] === dayOfWeek) {
                            totalDays++;
                            break;
                        }
                    }
                    
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                
                document.getElementById('totalDays').textContent = totalDays;
                updateTotalSchedules();
            }
            
            function updateTotalSchedules() {
                const totalDays = parseInt(document.getElementById('totalDays').textContent);
                const totalSlots = parseInt(document.getElementById('totalSlots').textContent);
                const total = totalDays * totalSlots;
                
                document.getElementById('totalSchedules').textContent = total;
            }
            
            function formatTime(date) {
                return date.toLocaleTimeString('es-ES', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: false
                });
            }
            
            function formatMinutes(minutes) {
                const hours = Math.floor(minutes / 60);
                const mins = minutes % 60;
                
                if (hours === 0) {
                    return `${mins}min`;
                } else if (mins === 0) {
                    return `${hours}h`;
                } else {
                    return `${hours}h ${mins}min`;
                }
            }
        </script>
    </body>
</html>
