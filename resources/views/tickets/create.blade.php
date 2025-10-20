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

                            <div class="mt-6">
                                <label for="maintenance_details" class="block text-sm font-medium text-gray-700 mb-2">
                                    Detalles adicionales del equipo <span class="text-red-500">*</span>
                                </label>
                                <textarea name="maintenance_details"
                                          id="maintenance_details"
                                          rows="4"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder="Describe cualquier falla actual, síntomas o comentarios relevantes para el mantenimiento.">{{ old('maintenance_details') }}</textarea>
                                @error('maintenance_details')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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
                            <div class="flex items-center gap-4 mb-4">
                                <label for="imagenes" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg cursor-pointer transition-colors duration-200 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Agregar Imágenes
                                </label>
                                <input type="file" 
                                       id="imagenes"
                                       multiple
                                       accept="image/*"
                                       class="hidden">
                                <span id="imageCount" class="text-sm text-gray-600">
                                    0 de 5 imágenes seleccionadas
                                </span>
                            </div>
                            
                            <p class="text-xs text-gray-500 mb-4">
                                PNG, JPG, GIF hasta 2MB por imagen. Máximo 5 imágenes.
                            </p>
                            
                            @error('imagenes.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview Container -->
                            <div id="imagePreviewContainer" class="hidden">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Imágenes seleccionadas:</h4>
                                <div id="imagePreviewGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                    <!-- Las previsualizaciones aparecerán aquí -->
                                </div>
                            </div>
                            
                            <!-- Hidden inputs para enviar los archivos -->
                            <div id="hiddenInputsContainer"></div>
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
            // Sistema de gestión de múltiples imágenes
            let selectedImages = [];
            const maxFiles = 5;
            const maxSize = 2 * 1024 * 1024; // 2MB en bytes
            
            document.getElementById('imagenes').addEventListener('change', function(e) {
                const newFiles = Array.from(e.target.files);
                
                // Verificar si agregar estos archivos excedería el límite
                if (selectedImages.length + newFiles.length > maxFiles) {
                    alert(`Solo puedes subir un máximo de ${maxFiles} imágenes. Actualmente tienes ${selectedImages.length} imágenes seleccionadas.`);
                    e.target.value = '';
                    return;
                }
                
                // Validar cada archivo nuevo
                for (let file of newFiles) {
                    // Verificar tamaño
                    if (file.size > maxSize) {
                        alert(`La imagen "${file.name}" excede el tamaño máximo de 2MB`);
                        e.target.value = '';
                        return;
                    }
                    
                    // Verificar si ya existe (por nombre y tamaño)
                    const isDuplicate = selectedImages.some(img => 
                        img.file.name === file.name && img.file.size === file.size
                    );
                    
                    if (isDuplicate) {
                        alert(`La imagen "${file.name}" ya está seleccionada`);
                        continue;
                    }
                    
                    // Agregar imagen con ID único
                    const imageId = Date.now() + Math.random();
                    selectedImages.push({
                        id: imageId,
                        file: file
                    });
                }
                
                // Limpiar el input para permitir seleccionar más archivos
                e.target.value = '';
                
                // Actualizar la interfaz
                updateImagePreviews();
                updateImageCount();
                updateHiddenInputs();
            });
            
            function removeImage(imageId) {
                selectedImages = selectedImages.filter(img => img.id !== imageId);
                updateImagePreviews();
                updateImageCount();
                updateHiddenInputs();
            }
            
            function updateImageCount() {
                const countElement = document.getElementById('imageCount');
                countElement.textContent = `${selectedImages.length} de ${maxFiles} imágenes seleccionadas`;
                
                // Cambiar color basado en la cantidad
                if (selectedImages.length === 0) {
                    countElement.className = 'text-sm text-gray-600';
                } else if (selectedImages.length >= maxFiles) {
                    countElement.className = 'text-sm text-red-600 font-medium';
                } else {
                    countElement.className = 'text-sm text-blue-600 font-medium';
                }
            }
            
            function updateImagePreviews() {
                const container = document.getElementById('imagePreviewContainer');
                const grid = document.getElementById('imagePreviewGrid');
                
                if (selectedImages.length === 0) {
                    container.classList.add('hidden');
                    grid.innerHTML = '';
                    return;
                }
                
                container.classList.remove('hidden');
                grid.innerHTML = '';
                
                selectedImages.forEach((imageObj, index) => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative group';
                        
                        previewDiv.innerHTML = `
                            <div class="aspect-square rounded-lg overflow-hidden border-2 border-gray-200 group-hover:border-blue-300 transition-colors relative">
                                <img src="${e.target.result}" 
                                     alt="Preview ${index + 1}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                    ${index + 1}
                                </div>
                                <button type="button" 
                                        onclick="removeImage(${imageObj.id})"
                                        class="absolute top-1 left-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                    ×
                                </button>
                            </div>
                            <div class="mt-1 text-xs text-gray-600 truncate" title="${imageObj.file.name}">
                                ${imageObj.file.name}
                            </div>
                            <div class="text-xs text-gray-500">
                                ${(imageObj.file.size / 1024 / 1024).toFixed(2)} MB
                            </div>
                        `;
                        
                        grid.appendChild(previewDiv);
                    };
                    
                    reader.readAsDataURL(imageObj.file);
                });
            }
            
            function updateHiddenInputs() {
                const container = document.getElementById('hiddenInputsContainer');
                container.innerHTML = '';
                
                selectedImages.forEach((imageObj, index) => {
                    // Crear un input file oculto para cada imagen
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.name = 'imagenes[]';
                    input.style.display = 'none';
                    
                    // Crear un DataTransfer para asignar el archivo al input
                    const dt = new DataTransfer();
                    dt.items.add(imageObj.file);
                    input.files = dt.files;
                    
                    container.appendChild(input);
                });
            }
            
            // Hacer la función removeImage global para que funcione desde el HTML
            window.removeImage = removeImage;

            // Calendario de mantenimiento
            initMaintenanceCalendar();

            // Inicializar contador
            updateImageCount();

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
                    empty: 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed'
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

                        if (status === 'full' || status === 'empty') {
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
                                const label = document.createElement('label');
                                label.className = 'flex items-center justify-between border rounded-lg px-3 py-2 cursor-pointer transition-colors duration-200 hover:border-blue-300';

                                const radio = document.createElement('input');
                                radio.type = 'radio';
                                radio.name = 'maintenance_slot_choice';
                                radio.value = slot.id;
                                radio.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300';

                                if (hiddenSlotInput.value && String(hiddenSlotInput.value) === String(slot.id)) {
                                    radio.checked = true;
                                    selectedSlotLabel.textContent = `Horario seleccionado: ${slot.label}`;
                                    matchedSlot = true;
                                }

                                radio.addEventListener('change', () => {
                                    hiddenSlotInput.value = slot.id;
                                    selectedSlotLabel.textContent = `Horario seleccionado: ${slot.label}`;
                                });

                                const infoContainer = document.createElement('div');
                                infoContainer.className = 'flex flex-col text-sm text-gray-700';
                                infoContainer.innerHTML = `
                                    <span class="font-semibold text-gray-900">${slot.label}</span>
                                    <span class="text-xs text-gray-500">${slot.available} lugar(es) disponible(s)</span>
                                `;

                                label.appendChild(radio);
                                label.appendChild(infoContainer);

                                timeSlotsList.appendChild(label);
                            });

                            if (!matchedSlot) {
                                hiddenSlotInput.value = '';
                                selectedSlotLabel.textContent = '';
                            }
                        })
                        .catch(() => {
                            noSlotsMessage.classList.remove('hidden');
                            noSlotsMessage.textContent = 'No se pudieron cargar los horarios disponibles.';
                        });
                }
            }

        </script>
    </body>
</html>
