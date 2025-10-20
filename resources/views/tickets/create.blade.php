<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Crear Ticket - {{ ucfirst($tipo) }} - Sistema IT</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900">Sistema de Tickets</h1>
                                    <p class="text-sm text-gray-600">E&I - Tecnología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <!-- Placeholder for consistency -->
                    </div>
                </div>
            </div>
        </header>

        <!-- Back to Home Button -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200 group">
                        <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver al Portal de Tickets
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-8">
                @php
                    $tipoConfig = [
                        'software' => [
                            'title' => 'Reportar Problema de Software',
                            'subtitle' => 'Reporta errores, fallos o comportamientos inesperados en programas o aplicaciones',
                            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                        ],
                        'hardware' => [
                            'title' => 'Reportar Problema de Hardware',
                            'subtitle' => 'Reporta fallas en computadoras, impresoras u otros equipos físicos',
                            'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
                        ],
                        'mantenimiento' => [
                            'title' => 'Programar Mantenimiento',
                            'subtitle' => 'Solicita mantenimiento preventivo o correctivo para tus equipos',
                            'icon' => 'M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z M12 12h.01M8 16h8'
                        ]
                    ];
                    
                    $config = $tipoConfig[$tipo];
                @endphp

                <div class="w-20 h-20 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                    </svg>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $config['title'] }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $config['subtitle'] }}
                </p>
            </div>

            <!-- Formulario -->
            <div class="bg-white shadow-xl rounded-xl border border-blue-100 p-8">
                <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tipo_problema" value="{{ $tipo }}">

                    <!-- Información del Usuario -->
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Información del Solicitante
                        </h3>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Nombre:</span>
                                    <p class="text-gray-900">{{ auth()->user()->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Correo:</span>
                                    <p class="text-gray-900">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del Problema/Solicitud -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Detalles del {{ $tipo === 'mantenimiento' ? 'Mantenimiento' : 'Problema' }}
                        </h3>

                        @if($tipo === 'software')
                            <div class="mb-6">
                                <label for="nombre_programa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Programa/Software
                                </label>
                                <input type="text" 
                                       name="nombre_programa" 
                                       id="nombre_programa"
                                       value="{{ old('nombre_programa') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       placeholder="Ej: Microsoft Word, Chrome, Sistema de ventas...">
                                @error('nombre_programa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label for="descripcion_problema" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($tipo === 'mantenimiento')
                                    Descripción del Mantenimiento Requerido <span class="text-red-500">*</span>
                                @else
                                    Descripción del Problema <span class="text-red-500">*</span>
                                @endif
                            </label>
                            <textarea name="descripcion_problema" 
                                      id="descripcion_problema"
                                      rows="5"
                                      required
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                      placeholder="{{ $tipo === 'mantenimiento' ? 
                                        'Describe qué tipo de mantenimiento necesitas, cuándo y cualquier detalle importante...' : 
                                        'Describe el problema con el mayor detalle posible. ¿Qué estabas haciendo cuando ocurrió? ¿Qué mensajes de error aparecen?' }}">{{ old('descripcion_problema') }}</textarea>
                            @error('descripcion_problema')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @if($tipo === 'mantenimiento')
                            <div class="mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z"></path>
                                    </svg>
                                    Agenda tu mantenimiento
                                </h4>
                                <input type="hidden" name="maintenance_time_slot_id" id="maintenance_time_slot_id" value="{{ old('maintenance_time_slot_id') }}">

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div class="lg:col-span-2 bg-white border border-green-200 rounded-lg p-4 shadow-sm">
                                        <div class="flex justify-between items-center mb-4">
                                            <button type="button" id="maintenancePrevMonth" class="text-sm text-blue-600 hover:text-blue-800 font-medium">&larr; Mes anterior</button>
                                            <div>
                                                <p id="maintenanceMonthLabel" class="text-lg font-semibold text-gray-800"></p>
                                                <p class="text-xs text-gray-500">Selecciona un día para ver horarios disponibles</p>
                                            </div>
                                            <button type="button" id="maintenanceNextMonth" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Mes siguiente &rarr;</button>
                                        </div>
                                        <div class="grid grid-cols-7 gap-2 text-xs font-semibold text-gray-500 mb-2">
                                            <div class="text-center">Lun</div>
                                            <div class="text-center">Mar</div>
                                            <div class="text-center">Mié</div>
                                            <div class="text-center">Jue</div>
                                            <div class="text-center">Vie</div>
                                            <div class="text-center">Sáb</div>
                                            <div class="text-center">Dom</div>
                                        </div>
                                        <div id="maintenanceCalendar" class="grid grid-cols-7 gap-2"></div>
                                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-4 h-4 rounded-full bg-green-200 border border-green-400"></span>
                                                <span class="text-gray-600">Disponible</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="w-4 h-4 rounded-full bg-yellow-200 border border-yellow-400"></span>
                                                <span class="text-gray-600">Reservado parcialmente</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="w-4 h-4 rounded-full bg-red-200 border border-red-400"></span>
                                                <span class="text-gray-600">Sin disponibilidad</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-inner">
                                        <h5 class="text-sm font-semibold text-gray-800 mb-3">Horarios disponibles</h5>
                                        <div id="maintenanceSlotList" class="space-y-3 text-sm text-gray-700">
                                            <p class="text-xs text-gray-500">Selecciona una fecha en el calendario.</p>
                                        </div>
                                    </div>
                                </div>
                                @error('maintenance_time_slot_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label for="maintenance_details" class="block text-sm font-medium text-gray-700 mb-2">
                                    Detalles adicionales del equipo o fallas observadas
                                </label>
                                <textarea name="maintenance_details"
                                          id="maintenance_details"
                                          rows="4"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder="Cuéntanos si el equipo presenta fallas específicas, ruidos, calentamiento u observaciones importantes para el mantenimiento.">{{ old('maintenance_details') }}</textarea>
                                @error('maintenance_details')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                    </div>

                    <!-- Imágenes -->
                    <div class="bg-orange-50 p-6 rounded-lg border border-orange-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Adjuntar Imágenes (Opcional)
                        </h3>
                        
                        <div>
                            <label for="imagenes" class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar Imágenes
                            </label>
                            <input type="file" 
                                   name="imagenes[]" 
                                   id="imagenes"
                                   multiple
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors duration-200">
                            <p class="mt-1 text-xs text-gray-500">
                                PNG, JPG, GIF hasta 2MB por imagen. Máximo 5 imágenes.
                            </p>
                            @error('imagenes.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between items-center pt-6">
                        <a href="{{ route('welcome') }}" 
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Cancelar
                        </a>

                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Ticket
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-blue-100 mt-16">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} Sistema de Tickets TI. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>

        <script>
            // Limitar el número de archivos seleccionados
            const imagenesInput = document.getElementById('imagenes');
            if (imagenesInput) {
                imagenesInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 5) {
                        alert('Máximo 5 imágenes permitidas');
                        e.target.value = '';
                    }
                });
            }

            @if($tipo === 'mantenimiento')
            const maintenanceData = @json($slots);
            const slotsByDate = maintenanceData.reduce((acc, slot) => {
                if (!acc[slot.date]) {
                    acc[slot.date] = [];
                }
                acc[slot.date].push(slot);
                return acc;
            }, {});

            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const calendarEl = document.getElementById('maintenanceCalendar');
            const slotListEl = document.getElementById('maintenanceSlotList');
            const monthLabelEl = document.getElementById('maintenanceMonthLabel');
            const prevBtn = document.getElementById('maintenancePrevMonth');
            const nextBtn = document.getElementById('maintenanceNextMonth');
            const hiddenSlotInput = document.getElementById('maintenance_time_slot_id');

            const statusClasses = {
                free: 'bg-green-100 border-green-300 text-green-900 hover:bg-green-200',
                partial: 'bg-yellow-100 border-yellow-300 text-yellow-900 hover:bg-yellow-200',
                full: 'bg-red-100 border-red-300 text-red-900 cursor-not-allowed',
                closed: 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'
            };

            const selectedClasses = 'ring-2 ring-blue-500';

            const formatDateKey = (dateObj) => {
                const year = dateObj.getFullYear();
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dateObj.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const formatMonthKey = (dateObj) => {
                const year = dateObj.getFullYear();
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                return `${year}-${month}`;
            };

            const today = new Date();
            let currentDate = maintenanceData.length
                ? new Date(maintenanceData[0].date + 'T00:00:00')
                : new Date(today.getFullYear(), today.getMonth(), 1);
            currentDate.setDate(1);

            let selectedDate = null;
            let selectedSlotId = hiddenSlotInput ? hiddenSlotInput.value : null;

            if (selectedSlotId) {
                const slot = maintenanceData.find(item => item.id.toString() === selectedSlotId.toString());
                if (slot) {
                    selectedDate = slot.date;
                    currentDate = new Date(slot.date + 'T00:00:00');
                    currentDate.setDate(1);
                }
            }

            function getDayStatus(dateString) {
                const slots = slotsByDate[dateString] || [];
                if (slots.length === 0) {
                    return 'closed';
                }

                const totalCapacity = slots.reduce((total, slot) => total + slot.capacity, 0);
                const totalAvailable = slots.reduce((total, slot) => total + slot.available, 0);
                const totalBooked = slots.reduce((total, slot) => total + slot.booked, 0);

                if (totalAvailable === 0) {
                    return 'full';
                }

                if (totalBooked === 0 && totalAvailable === totalCapacity) {
                    return 'free';
                }

                return 'partial';
            }

            function renderCalendar() {
                if (!calendarEl || !monthLabelEl) {
                    return;
                }

                calendarEl.innerHTML = '';

                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();

                monthLabelEl.textContent = `${monthNames[month]} ${year}`;

                const firstDay = new Date(year, month, 1);
                const paddingDays = (firstDay.getDay() + 6) % 7; // Ajustar para que lunes sea el primer día

                for (let i = 0; i < paddingDays; i++) {
                    const emptyCell = document.createElement('div');
                    calendarEl.appendChild(emptyCell);
                }

                const daysInMonth = new Date(year, month + 1, 0).getDate();

                for (let day = 1; day <= daysInMonth; day++) {
                    const dateObj = new Date(year, month, day);
                    const dateString = formatDateKey(dateObj);
                    const status = getDayStatus(dateString);

                    const dayButton = document.createElement('button');
                    dayButton.type = 'button';
                    dayButton.textContent = day;
                    dayButton.className = `p-2 rounded-lg border text-sm transition ${statusClasses[status] || statusClasses.closed}`;

                    const isPast = dateObj < new Date(today.getFullYear(), today.getMonth(), today.getDate());
                    if (status === 'closed' || status === 'full' || isPast) {
                        dayButton.disabled = true;
                        dayButton.classList.add('opacity-60');
                    }

                    if (selectedDate === dateString) {
                        dayButton.classList.add(...selectedClasses.split(' '));
                    }

                    if (!dayButton.disabled) {
                        dayButton.addEventListener('click', () => {
                            selectedDate = dateString;
                            renderCalendar();
                            renderSlotList(dateString);
                        });
                    }

                    calendarEl.appendChild(dayButton);
                }
            }

            function renderSlotList(dateString) {
                if (!slotListEl) {
                    return;
                }

                const slots = slotsByDate[dateString] || [];
                slotListEl.innerHTML = '';

                const title = document.createElement('p');
                title.className = 'text-xs text-gray-500 uppercase tracking-wide';
                title.textContent = `Horarios para ${dateString.split('-').reverse().join('/')}`;
                slotListEl.appendChild(title);

                if (slots.length === 0) {
                    const empty = document.createElement('p');
                    empty.className = 'text-sm text-gray-500 mt-2';
                    empty.textContent = 'No hay horarios disponibles en esta fecha.';
                    slotListEl.appendChild(empty);
                    hiddenSlotInput.value = '';
                    selectedSlotId = null;
                    return;
                }

                slots.forEach(slot => {
                    const available = slot.available;
                    const booked = slot.booked;
                    const timeLabel = slot.end_time ? `${slot.start_time} - ${slot.end_time}` : slot.start_time;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = `w-full text-left px-4 py-3 border rounded-lg transition ${available > 0 ? 'bg-white hover:border-blue-400 hover:shadow-md' : 'bg-red-50 border-red-200 text-red-600 cursor-not-allowed'}`;

                    const title = document.createElement('div');
                    title.className = 'flex justify-between items-center';
                    title.innerHTML = `<span class="font-semibold">${timeLabel}</span><span class="text-xs">${available} libres / ${slot.capacity} totales</span>`;

                    const note = document.createElement('p');
                    note.className = 'text-xs text-gray-500 mt-1';
                    note.textContent = slot.notes || (booked > 0 ? 'Algunos equipos ya asignados a este horario.' : 'Horario completamente disponible.');

                    button.appendChild(title);
                    button.appendChild(note);

                    if (slot.id.toString() === (selectedSlotId || '').toString()) {
                        button.classList.add('border-blue-500', 'ring', 'ring-blue-200');
                    }

                    if (available > 0) {
                        button.addEventListener('click', () => {
                            selectedSlotId = slot.id;
                            if (hiddenSlotInput) {
                                hiddenSlotInput.value = slot.id;
                            }
                            renderSlotList(dateString);
                        });
                    } else {
                        button.disabled = true;
                    }

                    slotListEl.appendChild(button);
                });
            }

            function changeMonth(delta) {
                currentDate.setMonth(currentDate.getMonth() + delta);
                renderCalendar();
                if (selectedDate) {
                    const monthString = formatMonthKey(currentDate);
                    if (!selectedDate.startsWith(monthString)) {
                        slotListEl.innerHTML = '<p class="text-xs text-gray-500">Selecciona una fecha en el calendario.</p>';
                    }
                }
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => changeMonth(-1));
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => changeMonth(1));
            }

            renderCalendar();
            if (selectedDate) {
                renderSlotList(selectedDate);
            }
            @endif
        </script>
    </body>
</html>