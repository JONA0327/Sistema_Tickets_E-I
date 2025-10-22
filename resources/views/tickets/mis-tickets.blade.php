<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mis Tickets - Sistema IT</title>

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
                                    <h1 class="text-xl font-bold text-gray-900">Mis Tickets</h1>
                                    <p class="text-sm text-gray-600">E&I - Tecnología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4" x-data="{ open: false }">
                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button 
                                @click="open = !open" 
                                @click.away="open = false"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div 
                                x-show="open" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                                style="display: none;">
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Cerrar Sesión
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Back to Home Button -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
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
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Información del Usuario -->
            <div class="bg-white shadow-xl rounded-lg border border-blue-100 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Mis Tickets</h2>
                
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Usuario:</p>
                            <p class="text-gray-900">{{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets del Usuario -->
            @if(count($tickets) > 0)
                    <div class="bg-white shadow-xl rounded-lg border border-blue-100">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Mis Tickets Registrados
                            </h3>
                            <p class="text-sm text-gray-600">{{ count($tickets) }} ticket(s) encontrado(s)</p>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($tickets as $ticket)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-semibold text-blue-600">{{ $ticket->folio }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->estado_badge }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($ticket->tipo_problema === 'software') bg-blue-100 text-blue-800
                                                @elseif($ticket->tipo_problema === 'hardware') bg-orange-100 text-orange-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($ticket->tipo_problema) }}
                                            </span>
                                        </div>

                                        @if($ticket->nombre_programa)
                                        @php
                                            $programLabel = match ($ticket->tipo_problema) {
                                                'hardware' => 'Tipo de equipo',
                                                'software' => 'Programa',
                                                default => 'Programa/Equipo',
                                            };
                                        @endphp
                                        <p class="text-sm text-gray-600 mb-1">
                                            <strong>{{ $programLabel }}:</strong> {{ $ticket->nombre_programa }}
                                        </p>
                                        @endif

                                        <p class="text-gray-700 mb-3">
                                            {{ Str::limit($ticket->descripcion_problema, 150) }}
                                        </p>

                                        @if($ticket->tipo_problema === 'mantenimiento')
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <span class="font-semibold text-gray-700 mr-2">Fecha programada:</span>
                                                    {{ optional($ticket->maintenance_scheduled_at)->format('d/m/Y H:i') ?? 'Por asignar' }}
                                                </div>
                                                @if($ticket->maintenance_details)
                                                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-gray-700">
                                                        <strong>Detalles del equipo:</strong> {{ $ticket->maintenance_details }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span>📅 {{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                                            @if($ticket->prioridad)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $ticket->prioridad_badge }}">
                                                    Prioridad: {{ ucfirst($ticket->prioridad) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($ticket->observaciones)
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <p class="text-sm text-yellow-800">
                                                <strong>Observaciones:</strong> {{ $ticket->observaciones }}
                                            </p>
                                        </div>
                                        @endif

                                        @if($ticket->imagenes && count($ticket->imagenes) > 0)
                                        <div class="mt-3">
                                            <p class="text-sm font-medium text-gray-700 mb-2">Imágenes adjuntas:</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($ticket->imagenes as $index => $imagen)
                                                    @php
                                                        // Manejar diferentes formatos de imagen
                                                        $imageSrc = '';
                                                        
                                                        if (is_array($imagen) && isset($imagen['data'])) {
                                                            // Formato array con data y mime separados
                                                            $imageSrc = "data:" . ($imagen['mime'] ?? 'image/jpeg') . ";base64," . $imagen['data'];
                                                        } elseif (is_string($imagen)) {
                                                            // Formato string completo data:image/type;base64,xxxxx
                                                            $imageSrc = $imagen;
                                                        }
                                                    @endphp
                                                    
                                                    @if($imageSrc)
                                                        <div class="relative group">
                                                            <img src="{{ $imageSrc }}" 
                                                                 alt="Imagen del ticket {{ $index + 1 }}" 
                                                                 class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-blue-400 cursor-pointer transition-all duration-300 hover:shadow-lg"
                                                                 onclick="expandImage(this)"
                                                                 title="Clic para expandir imagen">
                                                            <!-- Overlay con icono de zoom -->
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center pointer-events-none">
                                                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="w-32 h-32 bg-red-50 border-2 border-red-200 rounded-lg flex items-center justify-center">
                                                            <div class="text-center">
                                                                <svg class="w-8 h-8 text-red-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <p class="text-xs text-red-500">Error</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Acciones -->
                                    <div class="ml-6 flex flex-col space-y-2">
                                        @if($ticket->estado !== 'cerrado')
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $ticket->id }}', '{{ $ticket->folio }}')"
                                                class="bg-red-50 hover:bg-red-100 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center text-sm border border-red-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Eliminar
                                        </button>
                                        @else
                                        <span class="text-xs text-gray-500 italic">Ticket cerrado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white shadow-xl rounded-lg border border-blue-100 p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No tienes tickets registrados</h3>
                        <p class="text-gray-600 mb-4">Aún no has creado ningún ticket. ¡Crea tu primer ticket ahora!</p>
                        <a href="{{ route('welcome') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200 inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Primer Ticket
                        </a>
                    </div>
                @endif
        </main>



        <!-- Footer -->
        <footer class="bg-white border-t border-blue-100 mt-16">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} Sistema de Tickets TI. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            function confirmDelete(ticketId, folio) {
                if (confirm(`¿Estás seguro de que quieres eliminar el ticket ${folio}? Esta acción no se puede deshacer.`)) {
                    // Crear y enviar formulario de eliminación
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/ticket/' + ticketId;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            }

        </script>

        <script>
            function expandImage(imgElement) {
                const container = imgElement.parentElement;
                const isExpanded = container.classList.contains('expanded-image');
                
                if (isExpanded) {
                    // Contraer imagen
                    container.classList.remove('expanded-image');
                    imgElement.style.width = '8rem'; // w-32
                    imgElement.style.height = '8rem'; // h-32
                    imgElement.classList.add('object-cover');
                    imgElement.classList.remove('object-contain');
                    imgElement.title = 'Clic para expandir imagen';
                } else {
                    // Expandir imagen
                    container.classList.add('expanded-image');
                    imgElement.style.width = '20rem'; // Más ancho
                    imgElement.style.height = '16rem'; // Más alto pero proporcional
                    imgElement.classList.remove('object-cover');
                    imgElement.classList.add('object-contain');
                    imgElement.title = 'Clic para contraer imagen';
                }
            }
        </script>
    </body>
</html>