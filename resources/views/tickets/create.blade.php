 @extends('layouts.master')

@section('title', 'Crear Ticket - ' . ucfirst($tipo) . ' - Sistema IT')



@section('content')
        <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
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
                                    Programa/Software
                                </label>
                                <select name="nombre_programa" 
                                        id="nombre_programa"
                                        onchange="toggleOtroPrograma()"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <option value="">Selecciona un programa</option>
                                    <option value="Microsoft Outlook" {{ old('nombre_programa') === 'Microsoft Outlook' ? 'selected' : '' }}>Microsoft Outlook</option>
                                    <option value="Microsoft OneDrive" {{ old('nombre_programa') === 'Microsoft OneDrive' ? 'selected' : '' }}>Microsoft OneDrive</option>
                                    <option value="Microsoft Word" {{ old('nombre_programa') === 'Microsoft Word' ? 'selected' : '' }}>Microsoft Word</option>
                                    <option value="Microsoft Excel" {{ old('nombre_programa') === 'Microsoft Excel' ? 'selected' : '' }}>Microsoft Excel</option>
                                    <option value="Microsoft PowerPoint" {{ old('nombre_programa') === 'Microsoft PowerPoint' ? 'selected' : '' }}>Microsoft PowerPoint</option>
                                    <option value="Microsoft Teams" {{ old('nombre_programa') === 'Microsoft Teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                    <option value="Google Chrome" {{ old('nombre_programa') === 'Google Chrome' ? 'selected' : '' }}>Google Chrome</option>
                                    <option value="Mozilla Firefox" {{ old('nombre_programa') === 'Mozilla Firefox' ? 'selected' : '' }}>Mozilla Firefox</option>
                                    <option value="Microsoft Edge" {{ old('nombre_programa') === 'Microsoft Edge' ? 'selected' : '' }}>Microsoft Edge</option>
                                    <option value="Adobe Acrobat Reader" {{ old('nombre_programa') === 'Adobe Acrobat Reader' ? 'selected' : '' }}>Adobe Acrobat Reader</option>
                                    <option value="Zoom" {{ old('nombre_programa') === 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="Skype" {{ old('nombre_programa') === 'Skype' ? 'selected' : '' }}>Skype</option>
                                    <option value="WhatsApp Desktop" {{ old('nombre_programa') === 'WhatsApp Desktop' ? 'selected' : '' }}>WhatsApp Desktop</option>
                                    <option value="Sistema ERP" {{ old('nombre_programa') === 'Sistema ERP' ? 'selected' : '' }}>Sistema ERP</option>
                                    <option value="Sistema CRM" {{ old('nombre_programa') === 'Sistema CRM' ? 'selected' : '' }}>Sistema CRM</option>
                                    <option value="Sistema de Nómina" {{ old('nombre_programa') === 'Sistema de Nómina' ? 'selected' : '' }}>Sistema de Nómina</option>
                                    <option value="Sistema Contable" {{ old('nombre_programa') === 'Sistema Contable' ? 'selected' : '' }}>Sistema Contable</option>
                                    <option value="Antivirus" {{ old('nombre_programa') === 'Antivirus' ? 'selected' : '' }}>Antivirus</option>
                                    <option value="Otro" {{ old('nombre_programa') === 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('nombre_programa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <!-- Campo adicional para "Otro" -->
                                <div id="otroPrograma" class="mt-3 {{ old('nombre_programa') === 'Otro' ? '' : 'hidden' }}">
                                    <label for="otro_programa_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Especifica el nombre del programa/sistema
                                    </label>
                                    <input type="text" 
                                           name="otro_programa_nombre" 
                                           id="otro_programa_nombre"
                                           value="{{ old('otro_programa_nombre') }}"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                           placeholder="Ej: Sistema interno de la empresa, aplicación específica...">
                                    @error('otro_programa_nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @elseif($tipo === 'hardware')
                            <div class="mb-6">
                                <label for="tipo_equipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de equipo
                                </label>
                                <select name="nombre_programa"
                                        id="tipo_equipo"
                                        required
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <option value="">Selecciona el tipo de equipo</option>
                                    <option value="Computadora" {{ old('nombre_programa') === 'Computadora' ? 'selected' : '' }}>Computadora</option>
                                    <option value="Impresora" {{ old('nombre_programa') === 'Impresora' ? 'selected' : '' }}>Impresora</option>
                                </select>
                                @error('nombre_programa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label for="descripcion_problema" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($tipo === 'mantenimiento')
                                    Añadir detalles de problemas presentados en el equipo (Opcional)
                                @elseif($tipo === 'hardware')
                                    Descripción de la falla del equipo <span class="text-red-600">*</span>
                                @else
                                    Descripción de la falla del programa <span class="text-red-600">*</span>
                                @endif
                            </label>
                            <textarea name="descripcion_problema"
                                      id="descripcion_problema"
                                      rows="5"
                                      @if($tipo !== 'mantenimiento') required @endif
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                      placeholder="{{ $tipo === 'mantenimiento' ?
                                        'Describe qué tipo de mantenimiento necesitas, cuándo y cualquier detalle importante...'
 : ($tipo === 'hardware' ?
                                        'Describe la falla del equipo con el mayor detalle posible. ¿Qué estaba ocurriendo cuando falló? ¿Se muestran luces o mensajes en el dispositivo?'
 :
                                        'Describe la falla del programa con el mayor detalle posible. ¿Qué estabas haciendo cuando ocurrió? ¿Qué mensajes de error aparecen?' ) }}">{{ old('descripcion_problema') }}</textarea>
                        @error('descripcion_problema')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(in_array($tipo, ['software', 'hardware']))
                        <!-- Sección de imágenes para tickets de software y hardware -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Imágenes del problema (Opcional)
                            </label>
                            <p class="text-xs text-gray-500 mb-3">
                                Sube capturas de pantalla o fotos que ayuden a entender el problema. Máximo 5 imágenes.
                            </p>
                            
                            <!-- Input de archivos -->
                            <input type="file" 
                                   id="imageInput" 
                                   name="imagenes[]" 
                                   multiple 
                                   accept="image/*"
                                   class="hidden">
                            
                            <!-- Botón para subir imágenes -->
                            <button type="button" 
                                    id="uploadButton"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Seleccionar imágenes
                            </button>

                            <!-- Contador de imágenes -->
                            <span id="imageCount" class="ml-3 text-sm text-gray-500">0/5 imágenes</span>

                            <!-- Área de vista previa -->
                            <div id="imagePreview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"></div>

                            @error('imagenes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('imagenes.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($tipo === 'mantenimiento')
                        <div class="mt-6">
                            <h4 class="text-base font-semibold text-gray-800 mb-2">Agenda tu mantenimiento</h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Selecciona el día y horario disponible para tu mantenimiento. Los colores indican la disponibilidad: verde (disponible), amarillo (algunas horas reservadas) y rojo (sin horarios).
                            </p>

                            <input type="hidden" name="maintenance_slot_id" id="maintenance_slot_id" value="{{ old('maintenance_slot_id') }}">
                            <input type="hidden" name="maintenance_selected_date" id="maintenance_selected_date" value="{{ old('maintenance_selected_date') }}">

                            <div id="maintenanceScheduling"
                                 data-availability-url="{{ route('maintenance.availability') }}"
                                 data-slots-url="{{ route('maintenance.slots') }}"
                                 class="bg-white border border-green-200 rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <button type="button" id="calendarPrev" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Mes anterior
                                    </button>
                                    <div id="calendarMonthLabel" class="text-lg font-semibold text-gray-800"></div>
                                    <button type="button" id="calendarNext" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        Mes siguiente
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-7 gap-2 text-xs font-semibold text-gray-500 uppercase mb-2">
                                    <div class="text-center">Dom</div>
                                    <div class="text-center">Lun</div>
                                    <div class="text-center">Mar</div>
                                    <div class="text-center">Mié</div>
                                    <div class="text-center">Jue</div>
                                    <div class="text-center">Vie</div>
                                    <div class="text-center">Sáb</div>
                                </div>

                                <div id="calendarGrid" class="grid grid-cols-7 gap-2"></div>

                                <div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                                        Disponible
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                        Horarios reservados
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                        Sin disponibilidad
                                    </div>
                                </div>
                            </div>

                            @error('maintenance_slot_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div id="timeSlotsWrapper" class="mt-6 bg-white border border-blue-100 rounded-lg p-4 hidden">
                                <div class="flex items-center justify-between mb-3">
                                    <h5 class="text-sm font-semibold text-gray-700">Horarios disponibles para <span id="selectedDateLabel" class="text-blue-600"></span></h5>
                                    <span id="selectedSlotLabel" class="text-xs text-gray-500"></span>
                                </div>
                                <div id="timeSlotsList" class="grid gap-3 sm:grid-cols-2"></div>
                                <p id="noSlotsMessage" class="text-sm text-red-600 hidden">No hay horarios disponibles para la fecha seleccionada.</p>
                            </div>
                        </div>
                    @endif
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
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200 flex items-center"
                                onclick="return validateForm()">
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
            // Calendario de mantenimiento
            initMaintenanceCalendar();

            function initMaintenanceCalendar() {
                const schedulingContainer = document.getElementById('maintenanceScheduling');
                if (!schedulingContainer) {
                    return;
                }

                const availabilityUrl = schedulingContainer.dataset.availabilityUrl;
                const slotsUrl = schedulingContainer.dataset.slotsUrl;
                const calendarGrid = document.getElementById('calendarGrid');
                const monthLabel = document.getElementById('calendarMonthLabel');
                const prevButton = document.getElementById('calendarPrev');
                const nextButton = document.getElementById('calendarNext');
                const selectedDateLabel = document.getElementById('selectedDateLabel');
                const selectedSlotLabel = document.getElementById('selectedSlotLabel');
                const timeSlotsWrapper = document.getElementById('timeSlotsWrapper');
                const timeSlotsList = document.getElementById('timeSlotsList');
                const noSlotsMessage = document.getElementById('noSlotsMessage');
                const hiddenSlotInput = document.getElementById('maintenance_slot_id');
                const hiddenDateInput = document.getElementById('maintenance_selected_date');

                let availabilityByDay = {};
                let selectedDate = hiddenDateInput?.value || null;
                let currentDate = selectedDate ? new Date(`${selectedDate}T00:00:00`) : new Date();

                const statusClasses = {
                    available: 'bg-green-100 text-green-700 border border-green-200 hover:bg-green-200',
                    partial: 'bg-yellow-100 text-yellow-700 border border-yellow-200 hover:bg-yellow-200',
                    full: 'bg-red-100 text-red-700 border border-red-200 cursor-not-allowed',
                    empty: 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed',
                    past: 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed'
                };

                prevButton.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    fetchAvailability();
                });

                nextButton.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    fetchAvailability();
                });

                fetchAvailability().then(() => {
                    if (selectedDate) {
                        selectDate(selectedDate, true);
                    }
                });

                function fetchAvailability() {
                    const monthParam = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}`;
                    return fetch(`${availabilityUrl}?month=${monthParam}`)
                        .then(response => response.json())
                        .then(data => {
                            availabilityByDay = {};
                            (data.days || []).forEach(day => {
                                availabilityByDay[day.date] = day;
                            });
                            renderCalendar();
                        })
                        .catch(() => {
                            calendarGrid.innerHTML = '<p class="col-span-7 text-sm text-red-600">No se pudo cargar la disponibilidad.</p>';
                        });
                }

                function renderCalendar() {
                    calendarGrid.innerHTML = '';
                    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                    const totalDays = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
                    const startWeekDay = firstDay.getDay();

                    monthLabel.textContent = firstDay.toLocaleDateString('es-MX', {
                        month: 'long',
                        year: 'numeric'
                    }).replace(/^\w/u, c => c.toUpperCase());

                    for (let i = 0; i < startWeekDay; i++) {
                        const placeholder = document.createElement('div');
                        placeholder.className = 'h-12';
                        calendarGrid.appendChild(placeholder);
                    }

                    for (let day = 1; day <= totalDays; day++) {
                        const isoDate = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const info = availabilityByDay[isoDate];
                        const status = info ? info.status : 'empty';

                        const button = document.createElement('button');
                        button.type = 'button';
                        button.dataset.date = isoDate;
                        button.className = `h-12 rounded-lg flex flex-col items-center justify-center text-sm transition-colors duration-200 ${statusClasses[status] || statusClasses.empty}`;

                        if (status === 'full' || status === 'empty' || status === 'past' || info?.is_past) {
                            button.disabled = true;
                        } else {
                            button.classList.add('cursor-pointer');
                            button.addEventListener('click', () => selectDate(isoDate, true));
                        }

                        if (selectedDate === isoDate) {
                            button.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                        }

                        button.innerHTML = `
                            <span class="font-semibold">${day}</span>
                            <span class="text-[11px]">${info ? info.available : 0}/${info ? info.total_capacity : 0}</span>
                        `;

                        calendarGrid.appendChild(button);
                    }
                }

                function selectDate(isoDate, shouldLoadSlots = true) {
                    selectedDate = isoDate;
                    hiddenDateInput.value = isoDate;
                    renderCalendar();

                    if (shouldLoadSlots) {
                        loadSlots(isoDate);
                    }
                }

                function loadSlots(isoDate) {
                    timeSlotsList.innerHTML = '';
                    noSlotsMessage.classList.add('hidden');
                    noSlotsMessage.textContent = 'No hay horarios disponibles para la fecha seleccionada.';
                    timeSlotsWrapper.classList.remove('hidden');
                    selectedDateLabel.textContent = new Date(`${isoDate}T00:00:00`).toLocaleDateString('es-MX', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    selectedSlotLabel.textContent = '';

                    fetch(`${slotsUrl}?date=${isoDate}`)
                        .then(response => response.json())
                        .then(data => {
                            const slots = data.slots || [];

                            if (slots.length === 0) {
                                noSlotsMessage.classList.remove('hidden');
                                hiddenSlotInput.value = '';
                                return;
                            }

                            let matchedSlot = false;

                            slots.forEach(slot => {
                                const isUnavailable = slot.status === 'full' || slot.status === 'past' || slot.available <= 0;
                                const label = document.createElement('label');
                                label.className = 'flex items-center justify-between border rounded-lg px-3 py-2 transition-colors duration-200';

                                if (isUnavailable) {
                                    label.classList.add('bg-gray-100', 'border-gray-200', 'cursor-not-allowed', 'opacity-70');
                                } else {
                                    label.classList.add('cursor-pointer', 'hover:border-blue-300');
                                }

                                const radio = document.createElement('input');
                                radio.type = 'radio';
                                radio.name = 'maintenance_slot_choice';
                                radio.value = slot.id;
                                radio.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300';
                                radio.disabled = isUnavailable;

                                if (!isUnavailable && hiddenSlotInput.value && String(hiddenSlotInput.value) === String(slot.id)) {
                                    radio.checked = true;
                                    selectedSlotLabel.textContent = `Horario seleccionado: ${slot.label}`;
                                    matchedSlot = true;
                                }

                                if (!isUnavailable) {
                                    radio.addEventListener('change', () => {
                                        hiddenSlotInput.value = slot.id;
                                        selectedSlotLabel.textContent = `Horario seleccionado: ${slot.label}`;
                                    });
                                }

                                const infoContainer = document.createElement('div');
                                infoContainer.className = 'flex flex-col text-sm text-gray-700';
                                let availabilityText = `${slot.available} lugar(es) disponible(s)`;

                                if (slot.status === 'past') {
                                    availabilityText = 'Horario no disponible (pasado)';
                                } else if (slot.status === 'full') {
                                    availabilityText = 'Sin lugares disponibles';
                                }

                                infoContainer.innerHTML = `
                                    <span class="font-semibold text-gray-900">${slot.label}</span>
                                    <span class="text-xs ${isUnavailable ? 'text-gray-400' : 'text-gray-500'}">${availabilityText}</span>
                                `;

                                label.appendChild(radio);
                                label.appendChild(infoContainer);

                                timeSlotsList.appendChild(label);
                            });

                            if (!matchedSlot) {
                                hiddenSlotInput.value = '';
                                selectedSlotLabel.textContent = '';
                            }

                            if (!slots.some(slot => slot.status !== 'past' && slot.status !== 'full' && slot.available > 0)) {
                                noSlotsMessage.classList.remove('hidden');
                                noSlotsMessage.textContent = 'No hay horarios disponibles para la fecha seleccionada.';
                            }
                        })
                        .catch(() => {
                            noSlotsMessage.classList.remove('hidden');
                            noSlotsMessage.textContent = 'No se pudieron cargar los horarios disponibles.';
                        });
                }
            }

            // Sistema de imágenes para tickets de software y hardware
            @if(in_array($tipo, ['software', 'hardware']))
            initImageUpload();

            function initImageUpload() {
                const imageInput = document.getElementById('imageInput');
                const uploadButton = document.getElementById('uploadButton');
                const imagePreview = document.getElementById('imagePreview');
                const imageCount = document.getElementById('imageCount');
                let selectedFiles = new DataTransfer();
                const maxFiles = 5;

                uploadButton.addEventListener('click', () => {
                    imageInput.click();
                });

                imageInput.addEventListener('change', (e) => {
                    const files = Array.from(e.target.files);
                    
                    files.forEach(file => {
                        if (selectedFiles.files.length < maxFiles) {
                            if (file.type.startsWith('image/')) {
                                selectedFiles.items.add(file);
                            }
                        }
                    });

                    updateImagePreview();
                    imageInput.files = selectedFiles.files;
                });

                function updateImagePreview() {
                    imagePreview.innerHTML = '';
                    imageCount.textContent = `${selectedFiles.files.length}/${maxFiles} imágenes`;

                    Array.from(selectedFiles.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const imageContainer = document.createElement('div');
                            imageContainer.className = 'relative group';

                            imageContainer.innerHTML = `
                                <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                     class="w-full h-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                                <button type="button" 
                                        onclick="removeImage(${index})" 
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                    ×
                                </button>
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b-lg truncate">
                                    ${file.name}
                                </div>
                            `;

                            imagePreview.appendChild(imageContainer);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                window.removeImage = function(index) {
                    const files = Array.from(selectedFiles.files);
                    files.splice(index, 1);
                    
                    selectedFiles = new DataTransfer();
                    files.forEach(file => selectedFiles.items.add(file));
                    
                    imageInput.files = selectedFiles.files;
                    updateImagePreview();
                };
            }
            @endif

            @if($tipo === 'software')
            // Función para mostrar/ocultar el campo "Otro programa" (solo para tickets de software)
            function toggleOtroPrograma() {
                const select = document.getElementById('nombre_programa');
                const otroDiv = document.getElementById('otroPrograma');
                const otroInput = document.getElementById('otro_programa_nombre');
                
                // Verificar que todos los elementos existen
                if (!select || !otroDiv || !otroInput) {
                    return;
                }
                
                if (select.value === 'Otro') {
                    otroDiv.classList.remove('hidden');
                    otroInput.focus();
                } else {
                    otroDiv.classList.add('hidden');
                    otroInput.value = '';
                }
            }

            // Inicializar cuando la página carga
            document.addEventListener('DOMContentLoaded', function() {
                toggleOtroPrograma();
            });
            @endif

            // Validación del formulario
            function validateForm() {
                @if($tipo === 'mantenimiento')
                const slotId = document.getElementById('maintenance_slot_id').value;
                if (!slotId) {
                    alert('Por favor selecciona un horario de mantenimiento antes de crear el ticket.');
                    return false;
                }
                console.log('Slot seleccionado:', slotId);
                @endif

                @if($tipo === 'hardware')
                const equipmentType = document.getElementById('tipo_equipo');
                const descriptionField = document.getElementById('descripcion_problema');

                if (!equipmentType.value) {
                    alert('Selecciona el tipo de equipo que presenta la falla.');
                    equipmentType.focus();
                    return false;
                }

                if (!descriptionField.value.trim()) {
                    alert('Describe la falla del equipo para poder atender tu solicitud.');
                    descriptionField.focus();
                    return false;
                }
                @endif

                console.log('Formulario validado correctamente');
                return true;
            }

        </script>
@endsection
