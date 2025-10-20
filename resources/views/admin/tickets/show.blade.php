<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ticket {{ $ticket->folio }} - Panel Administrativo</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                                    <h1 class="text-xl font-bold text-gray-900">Ticket {{ $ticket->folio }}</h1>
                                    <p class="text-sm text-gray-600">E&I - Tecnología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lista de Tickets
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Panel Admin
                        </a>
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 text-sm rounded-full bg-blue-50 p-2 text-gray-700 hover:bg-blue-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                                        Administrador TI
                                    </div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-800 transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Back to Home Button -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                @if (!Auth::user()->isAdmin())
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200 group">
                        <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver al Portal de Tickets
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Detalles del Ticket -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Información Principal -->
                    <div class="bg-white shadow-xl rounded-lg border border-blue-100 p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $ticket->folio }}</h2>
                                <p class="text-gray-600">Creado el {{ $ticket->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ticket->estado_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}
                                </span>
                                @if($ticket->prioridad)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ticket->prioridad_badge }}">
                                        {{ ucfirst($ticket->prioridad) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Información del Solicitante -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información del Solicitante
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nombre</p>
                                    <p class="text-lg text-gray-900">{{ $ticket->nombre_solicitante }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Correo</p>
                                    <p class="text-lg text-blue-600">
                                        <a href="mailto:{{ $ticket->correo_solicitante }}">{{ $ticket->correo_solicitante }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del Problema -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Detalles del {{ $ticket->tipo_problema === 'mantenimiento' ? 'Mantenimiento' : 'Problema' }}
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tipo</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($ticket->tipo_problema === 'software') bg-blue-100 text-blue-800
                                        @elseif($ticket->tipo_problema === 'hardware') bg-orange-100 text-orange-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($ticket->tipo_problema) }}
                                    </span>
                                </div>

                                @if($ticket->nombre_programa)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Programa/Software</p>
                                    <p class="text-lg text-gray-900">{{ $ticket->nombre_programa }}</p>
                                </div>
                                @endif

                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-2">Descripción</p>
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <p class="text-gray-900 leading-relaxed">{{ $ticket->descripcion_problema }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($ticket->tipo_problema === 'mantenimiento')
                            @php
                                $slot = $ticket->maintenanceSlot;
                                $selectedComponents = $ticket->replacement_components ?? [];
                                $profile = $ticket->computerProfile;
                            @endphp

                            <div class="bg-green-50 p-6 rounded-lg border border-green-200 mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z" />
                                    </svg>
                                    Programación del mantenimiento
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Fecha programada</p>
                                        <p class="font-semibold text-gray-900">{{ optional($ticket->maintenance_scheduled_at)->format('d/m/Y H:i') ?? 'Sin definir' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Horario</p>
                                        <p class="font-semibold text-gray-900">
                                            @if($slot)
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                            @else
                                                Horario por asignar
                                            @endif
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Detalles proporcionados por el usuario</p>
                                        <p class="mt-1 text-gray-800">{{ $ticket->maintenance_details }}</p>
                                    </div>
                                    @if(!empty($selectedComponents))
                                        <div class="md:col-span-2">
                                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-2">Componentes sugeridos para reemplazo</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($selectedComponents as $component)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                        {{ ucfirst(str_replace('_', ' ', $component)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h2l.4 2M7 7h14l-1.68 8.39a2 2 0 01-1.97 1.61H8.25a2 2 0 01-1.97-1.61L5 4H3" />
                                    </svg>
                                    Ficha técnica del equipo
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Identificador</p>
                                        <p class="font-semibold text-gray-900">{{ $ticket->equipment_identifier ?? 'Sin asignar' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Marca y modelo</p>
                                        <p class="font-semibold text-gray-900">{{ trim(($ticket->equipment_brand ?? '').' '.($ticket->equipment_model ?? '')) ?: 'No especificado' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Tipo de disco</p>
                                        <p class="font-semibold text-gray-900">{{ $ticket->disk_type ?? 'Sin registro' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Capacidad de RAM</p>
                                        <p class="font-semibold text-gray-900">{{ $ticket->ram_capacity ?? 'Sin registro' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Estado de batería</p>
                                        <p class="font-semibold text-gray-900">{{ $ticket->battery_status ? ucfirst(str_replace('_', ' ', $ticket->battery_status)) : 'No evaluada' }}</p>
                                    </div>
                                    @if($ticket->aesthetic_observations)
                                        <div class="md:col-span-2">
                                            <p class="text-gray-500 text-xs uppercase tracking-wider">Observaciones estéticas</p>
                                            <p class="mt-1 text-gray-800">{{ $ticket->aesthetic_observations }}</p>
                                        </div>
                                    @endif
                                    @if($profile && $profile->is_loaned)
                                        <div class="md:col-span-2 bg-green-50 border border-green-200 rounded-lg p-4">
                                            <p class="text-sm font-semibold text-green-700">Equipo prestado actualmente</p>
                                            <p class="text-sm text-green-700">{{ $profile->loaned_to_name }} - {{ $profile->loaned_to_email }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($ticket->maintenance_report || $ticket->closure_observations)
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h13m-7 0V7a4 4 0 10-8 0v4m-2 0h12" />
                                        </svg>
                                        Reportes del mantenimiento
                                    </h3>
                                    <div class="space-y-4 text-sm text-gray-700">
                                        @if($ticket->maintenance_report)
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Informe técnico</p>
                                                <p class="mt-1 text-gray-800">{{ $ticket->maintenance_report }}</p>
                                            </div>
                                        @endif
                                        @if($ticket->closure_observations)
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Observaciones al cerrar</p>
                                                <p class="mt-1 text-gray-800">{{ $ticket->closure_observations }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Imágenes -->
                        @php
                            // Debug para entender el problema
                            $imagenes = $ticket->imagenes;
                            $isArray = is_array($imagenes);
                            $isEmpty = empty($imagenes);
                        @endphp
                        
                        @if($imagenes && $isArray && !$isEmpty)
                        <div class="bg-orange-50 p-6 rounded-lg border border-orange-100 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Imágenes Adjuntas ({{ count($imagenes) }})
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($imagenes as $index => $imagen)
                                    @php
                                        // Manejar diferentes formatos de imagen
                                        $imageSrc = '';
                                        $imageName = 'Imagen del ticket ' . ($index + 1);
                                        
                                        if (is_array($imagen) && isset($imagen['data'])) {
                                            // Formato array con data y mime separados
                                            $imageSrc = "data:" . ($imagen['mime'] ?? 'image/jpeg') . ";base64," . $imagen['data'];
                                            $imageName = $imagen['name'] ?? $imageName;
                                        } elseif (is_string($imagen)) {
                                            // Formato string completo data:image/type;base64,xxxxx
                                            $imageSrc = $imagen;
                                        }
                                    @endphp
                                    
                                    @if($imageSrc)
                                        <div class="relative group">
                                            <img src="{{ $imageSrc }}" 
                                                alt="{{ $imageName }}" 
                                                class="w-full h-48 object-cover rounded-lg border-2 border-gray-200 hover:border-blue-400 transition-all duration-300 hover:shadow-xl cursor-pointer"
                                                onclick="openImageModal(this)"
                                                title="Clic para ver en grande">
                                            <!-- Overlay con icono de zoom -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center pointer-events-none">
                                                <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                </svg>
                                            </div>
                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 rounded-b-lg">
                                                {{ $imageName }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="relative bg-red-50 p-4 rounded-lg border border-red-200">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-sm text-red-600">Error al cargar imagen {{ $index + 1 }}</p>
                                            </div>
                                            <p class="text-xs text-red-500 mt-1">Formato de imagen no válido</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Imágenes
                            </h3>
                            <p class="text-gray-600">No hay imágenes adjuntas en este ticket.</p>
                        </div>
                        @endif

                        <!-- Observaciones -->
                        @if($ticket->observaciones)
                        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                Observaciones del Administrador
                            </h3>
                            <div class="bg-white p-4 rounded-lg border border-yellow-200">
                                <p class="text-gray-900">{{ $ticket->observaciones }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Panel de Gestión -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-xl rounded-lg border border-blue-100 p-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Gestionar Ticket</h3>

                        <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado del Ticket
                                </label>
                                <select name="estado" 
                                        id="estado"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <option value="abierto" {{ $ticket->estado === 'abierto' ? 'selected' : '' }}>Abierto</option>
                                    <option value="en_proceso" {{ $ticket->estado === 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="cerrado" {{ $ticket->estado === 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                                </select>
                            </div>

                            @if($ticket->tipo_problema !== 'mantenimiento')
                                <!-- Prioridad -->
                                <div>
                                    <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-2">
                                        Prioridad
                                    </label>
                                    <select name="prioridad"
                                            id="prioridad"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                        <option value="">Sin asignar</option>
                                        <option value="baja" {{ $ticket->prioridad === 'baja' ? 'selected' : '' }}>Baja</option>
                                        <option value="media" {{ $ticket->prioridad === 'media' ? 'selected' : '' }}>Media</option>
                                        <option value="alta" {{ $ticket->prioridad === 'alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="critica" {{ $ticket->prioridad === 'critica' ? 'selected' : '' }}>Crítica</option>
                                    </select>
                                </div>
                            @else
                                <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-3 py-2 rounded-lg">
                                    Este ticket no maneja prioridades. Administra el orden desde la agenda de mantenimientos.
                                </div>
                            @endif

                            <!-- Observaciones -->
                            <div>
                                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observaciones del Administrador
                                </label>
                                <textarea name="observaciones"
                                          id="observaciones"
                                          rows="4"
                                          placeholder="Agregar notas, comentarios o instrucciones..."
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">{{ $ticket->observaciones }}</textarea>
                            </div>

                            @if($ticket->tipo_problema === 'mantenimiento')
                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Datos del equipo</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="equipment_identifier" class="block text-xs font-medium text-gray-600 mb-1">Identificador del equipo</label>
                                            <input type="text" id="equipment_identifier" name="equipment_identifier" value="{{ old('equipment_identifier', $ticket->equipment_identifier) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @error('equipment_identifier')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="equipment_brand" class="block text-xs font-medium text-gray-600 mb-1">Marca</label>
                                                <input type="text" id="equipment_brand" name="equipment_brand" value="{{ old('equipment_brand', $ticket->equipment_brand) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                @error('equipment_brand')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                            </div>
                                            <div>
                                                <label for="equipment_model" class="block text-xs font-medium text-gray-600 mb-1">Modelo</label>
                                                <input type="text" id="equipment_model" name="equipment_model" value="{{ old('equipment_model', $ticket->equipment_model) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                @error('equipment_model')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                            </div>
                                            <div>
                                                <label for="disk_type" class="block text-xs font-medium text-gray-600 mb-1">Tipo de disco</label>
                                                <input type="text" id="disk_type" name="disk_type" value="{{ old('disk_type', $ticket->disk_type) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                @error('disk_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                            </div>
                                            <div>
                                                <label for="ram_capacity" class="block text-xs font-medium text-gray-600 mb-1">Capacidad de RAM</label>
                                                <input type="text" id="ram_capacity" name="ram_capacity" value="{{ old('ram_capacity', $ticket->ram_capacity) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                @error('ram_capacity')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label for="battery_status" class="block text-xs font-medium text-gray-600 mb-1">Estado de batería</label>
                                            <select id="battery_status" name="battery_status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Selecciona una opción</option>
                                                <option value="functional" {{ old('battery_status', $ticket->battery_status) === 'functional' ? 'selected' : '' }}>Funcional</option>
                                                <option value="partially_functional" {{ old('battery_status', $ticket->battery_status) === 'partially_functional' ? 'selected' : '' }}>Parcialmente funcional</option>
                                                <option value="damaged" {{ old('battery_status', $ticket->battery_status) === 'damaged' ? 'selected' : '' }}>Dañada</option>
                                            </select>
                                            @error('battery_status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label for="aesthetic_observations" class="block text-xs font-medium text-gray-600 mb-1">Observaciones estéticas</label>
                                            <textarea id="aesthetic_observations" name="aesthetic_observations" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('aesthetic_observations', $ticket->aesthetic_observations) }}</textarea>
                                            @error('aesthetic_observations')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Reportes y observaciones</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="maintenance_report" class="block text-xs font-medium text-gray-600 mb-1">Reporte técnico</label>
                                            <textarea id="maintenance_report" name="maintenance_report" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('maintenance_report', $ticket->maintenance_report) }}</textarea>
                                            @error('maintenance_report')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label for="closure_observations" class="block text-xs font-medium text-gray-600 mb-1">Observaciones al cerrar</label>
                                            <textarea id="closure_observations" name="closure_observations" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('closure_observations', $ticket->closure_observations) }}</textarea>
                                            @error('closure_observations')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Componentes para reemplazo</h4>
                                    @php
                                        $componentOptions = [
                                            'disco_duro' => 'Disco duro',
                                            'ram' => 'RAM',
                                            'bateria' => 'Batería',
                                            'pantalla' => 'Pantalla',
                                            'conectores' => 'Conectores',
                                            'teclado' => 'Teclado',
                                            'mousepad' => 'Mousepad',
                                            'cargador' => 'Cargador',
                                        ];
                                        $selected = old('replacement_components', $ticket->replacement_components ?? []);
                                    @endphp
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($componentOptions as $value => $label)
                                            <label class="inline-flex items-center text-xs text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                                                <input type="checkbox" name="replacement_components[]" value="{{ $value }}" class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ is_array($selected) && in_array($value, $selected) ? 'checked' : '' }}>
                                                {{ $label }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('replacement_components')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror
                                    @error('replacement_components.*')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror
                                </div>

                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <label class="flex items-start text-sm text-gray-700">
                                        <input type="checkbox" name="mark_as_loaned" value="1" class="mt-1 mr-2 rounded border-gray-300 text-green-600 focus:ring-green-500" {{ old('mark_as_loaned', optional($ticket->computerProfile)->is_loaned) ? 'checked' : '' }}>
                                        <span>Marcar equipo como prestado a {{ $ticket->nombre_solicitante }}<br><span class="text-xs text-gray-500">Se registrará que el equipo permanece bajo custodia del solicitante.</span></span>
                                    </label>
                                </div>
                            @endif

                            <!-- Información de Fechas -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Información de Fechas</h4>
                                
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Apertura:</span>
                                        <span class="text-gray-900">{{ $ticket->fecha_apertura->format('d/m/Y H:i') }}</span>
                                    </div>
                                    
                                    @if($ticket->fecha_cierre)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Cierre:</span>
                                        <span class="text-gray-900">{{ $ticket->fecha_cierre->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @endif
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Última actualización:</span>
                                        <span class="text-gray-900">{{ $ticket->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Actualizar -->
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Actualizar Ticket
                            </button>
                        </form>

                        <!-- Acción Rápida: Contactar Solicitante -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="mailto:{{ $ticket->correo_solicitante }}?subject=Ticket {{ $ticket->folio }} - Seguimiento&body=Estimado/a {{ $ticket->nombre_solicitante }},

Referente a su ticket {{ $ticket->folio }}..."
                               class="w-full bg-green-50 hover:bg-green-100 text-green-700 font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center border border-green-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contactar Solicitante
                            </a>

                            <!-- Archivar Problema (solo para tickets cerrados) -->
                            @if($ticket->estado === 'cerrado')
                                <div class="mt-3">
                                    <a href="{{ route('archivo-problemas.create', $ticket->id) }}"
                                       class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center border border-purple-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        📚 Archivar en Base de Conocimiento
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>



        <!-- Modal para visualizar imágenes -->
        <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4" onclick="closeImageModal()">
            <div class="relative max-w-5xl max-h-full" onclick="event.stopPropagation()">
                <button type="button" onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors duration-200 z-10" aria-label="Cerrar">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="" class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl bg-white">
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-3 rounded-b-lg">
                    <p id="modalImageName" class="text-sm font-medium"></p>
                </div>
            </div>
        </div>

        <script>
            function openImageModal(imgElement) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                const modalImageName = document.getElementById('modalImageName');

                modalImage.src = imgElement.src;
                modalImage.alt = imgElement.alt || 'Imagen del ticket';
                modalImageName.textContent = imgElement.alt || 'Imagen del ticket';

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Cerrar con tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });

            function expandImage(imgElement) {
                const container = imgElement.parentElement;
                const isExpanded = container.classList.contains('expanded-image');
                
                if (isExpanded) {
                    // Contraer imagen
                    container.classList.remove('expanded-image');
                    imgElement.style.height = '12rem'; // h-48
                    imgElement.classList.add('object-cover');
                    imgElement.classList.remove('object-contain');
                    imgElement.title = 'Clic para expandir imagen';
                } else {
                    // Expandir imagen
                    container.classList.add('expanded-image');
                    imgElement.style.height = '24rem'; // Mucho más grande pero no exagerado
                    imgElement.classList.remove('object-cover');
                    imgElement.classList.add('object-contain');
                    imgElement.title = 'Clic para contraer imagen';
                }
            }
        </script>
    </body>
</html>