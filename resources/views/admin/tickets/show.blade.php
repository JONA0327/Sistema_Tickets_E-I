@extends('layouts.master')

@section('title', 'Ticket ' . $ticket->folio . ' - Panel Administrativo')

@section('header')
<!-- Navegación rápida -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        @if (!Auth::user()->isAdmin())
        <div class="flex items-center justify-center sm:justify-start">
            <a href="{{ route('welcome') }}"
               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200 group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver al Portal de Tickets
            </a>
        </div>
        @endif
        <div class="flex items-center justify-center sm:justify-end">
            <a href="{{ route('admin.tickets.index') }}"
               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                Lista de Tickets
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
        <main class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
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
                        @if($ticket->closed_by_user)
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start space-x-3">
                                <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-red-800">
                                    <p class="font-semibold">Ticket cancelado por el usuario.</p>
                                    <p class="mt-1">
                                        {{ $ticket->nombre_solicitante }} cerró el folio {{ $ticket->folio }}
                                        @if($ticket->closed_by_user_at)
                                            el {{ $ticket->closed_by_user_at->format('d/m/Y \a \l\a\s H:i') }}.
                                        @else
                                            recientemente.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

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
                                @php
                                    $programLabel = match ($ticket->tipo_problema) {
                                        'hardware' => 'Tipo de equipo',
                                        'software' => 'Programa/Software',
                                        default => 'Programa/Equipo',
                                    };
                                @endphp
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ $programLabel }}</p>
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
                                    @if($ticket->equipment_password)
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider">Contraseña del equipo</p>
                                        <div class="flex items-center space-x-2">
                                            <span id="password-text" class="font-semibold text-gray-900 bg-gray-100 px-2 py-1 rounded" style="display: none;">{{ $ticket->equipment_password }}</span>
                                            <span id="password-hidden" class="font-semibold text-gray-900">••••••••</span>
                                            <button type="button" onclick="togglePassword()" class="text-blue-600 hover:text-blue-800 text-xs underline">
                                                <span id="toggle-text">Mostrar</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
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
                        
                        @if(($imagenes && $isArray && !$isEmpty) || ($ticket->imagenes_admin && count($ticket->imagenes_admin) > 0))
                        <div class="bg-orange-50 p-6 rounded-lg border border-orange-100 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Imágenes Adjuntas 
                                @php
                                    $totalImages = ($imagenes && $isArray ? count($imagenes) : 0) + ($ticket->imagenes_admin ? count($ticket->imagenes_admin) : 0);
                                @endphp
                                ({{ $totalImages }})
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
                            
                            <!-- Agregar imágenes del administrador si existen -->
                            @if($ticket->imagenes_admin && count($ticket->imagenes_admin) > 0)
                                <div class="mt-6 pt-6 border-t border-orange-200">
                                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-4 h-4 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        Imágenes del Administrador ({{ count($ticket->imagenes_admin) }})
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($ticket->imagenes_admin as $index => $imagen)
                                            <div class="relative group">
                                                <img src="data:image/jpeg;base64,{{ $imagen }}" 
                                                     alt="Imagen admin {{ $index + 1 }}" 
                                                     class="w-full h-32 object-cover rounded-lg border-2 border-orange-200 cursor-pointer hover:border-orange-400 transition-all duration-200 shadow-md hover:shadow-lg" 
                                                     onclick="openImageModal('data:image/jpeg;base64,{{ $imagen }}', 'Imagen del Administrador {{ $index + 1 }}')">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        @else
                            <!-- Mostrar solo imágenes del admin si las hay -->
                            @if($ticket->imagenes_admin && count($ticket->imagenes_admin) > 0)
                                <div class="bg-orange-50 p-6 rounded-lg border border-orange-100 mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Imágenes del Administrador ({{ count($ticket->imagenes_admin) }})
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($ticket->imagenes_admin as $index => $imagen)
                                            <div class="relative group">
                                                <img src="data:image/jpeg;base64,{{ $imagen }}" 
                                                     alt="Imagen admin {{ $index + 1 }}" 
                                                     class="w-full h-32 object-cover rounded-lg border-2 border-orange-200 cursor-pointer hover:border-orange-400 transition-all duration-200 shadow-md hover:shadow-lg" 
                                                     onclick="openImageModal('data:image/jpeg;base64,{{ $imagen }}', 'Imagen del Administrador {{ $index + 1 }}')">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 p-6 rounded-lg border border-gray-100 mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Imágenes
                                    </h3>
                                    <p class="text-gray-600">No hay imágenes adjuntas en este ticket.</p>
                                </div>
                            @endif
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
                    <div class="bg-white shadow-xl rounded-lg border border-blue-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Gestionar Ticket</h3>

                        <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="space-y-6" enctype="multipart/form-data">
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
                                <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-medium px-3 py-2 rounded-lg">
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
                                <!-- Datos del equipo -->
                                <div class="mt-6">
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
                                        </div>
                                        <div>
                                            <label for="equipment_password" class="block text-xs font-medium text-gray-600 mb-1">Contraseña del equipo</label>
                                            <input type="password" id="equipment_password" name="equipment_password" value="{{ old('equipment_password', $ticket->equipment_password) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contraseña de acceso al equipo">
                                            @error('equipment_password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                                        <!-- Imágenes del administrador -->
                                        <div class="mt-6">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Imágenes del administrador</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="imagenes_admin" class="block text-xs font-medium text-gray-600 mb-1">Anexar imágenes (solo administrador)</label>
                                            <input type="file" id="imagenes_admin" name="imagenes_admin[]" multiple accept="image/*" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                            <p class="text-xs text-gray-500 mt-1">
                                                <strong>📸 Múltiples archivos:</strong> Puedes seleccionar varias imágenes a la vez.<br>
                                                <strong>🔍 Vista previa:</strong> Haz click en cualquier imagen para verla en tamaño completo.<br>
                                                <strong>📍 Ubicación:</strong> Las imágenes también aparecerán en la sección "Imágenes" principal.
                                                <br>
                                                <button type="button" onclick="testModal()" class="mt-2 px-3 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200">🧪 Probar Modal</button>
                                            </p>
                                            @error('imagenes_admin')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                            @error('imagenes_admin.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        
                                        <!-- Preview de imágenes -->
                                        <div id="imagePreviewAdmin" class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4" style="display: none;">
                                        </div>
                                        
                                        <!-- Mensaje de estado -->
                                        <div id="uploadStatus" class="hidden mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <p class="text-sm text-blue-700">
                                                <span id="fileCount">0</span> archivo(s) seleccionado(s). 
                                                <span class="font-medium">Recuerda hacer clic en "Actualizar" para guardar los cambios.</span>
                                            </p>
                                        </div>
                                        
                                        <!-- Imágenes existentes -->
                                        @if($ticket->imagenes_admin && count($ticket->imagenes_admin) > 0)
                                            <div class="mt-4">
                                                <h5 class="text-xs font-medium text-gray-600 mb-2">Imágenes existentes ({{ count($ticket->imagenes_admin) }}):</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                    @foreach($ticket->imagenes_admin as $index => $imagen)
                                                        <div class="relative group">
                                                            <img src="data:image/jpeg;base64,{{ $imagen }}" alt="Imagen {{ $index + 1 }}" class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-75 hover:border-blue-400 transition-all shadow-sm hover:shadow-md" onclick="openImageModal('data:image/jpeg;base64,{{ $imagen }}', 'Imagen Administrador {{ $index + 1 }}')">
                                                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <button type="button" onclick="removeExistingAdminImage({{ $index }})" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 text-xs shadow-lg">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="absolute bottom-1 left-1 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                                                                Img {{ $index + 1 }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                            </div>
                                        </div>

                                        <!-- Reportes y observaciones -->
                                        <div class="mt-6">
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

                                <!-- Componentes para reemplazo -->
                                <div class="mt-6">
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

                                <!-- Marcar como prestado -->
                                <div class="mt-6">
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
                        <div class="pt-6 border-t border-gray-200">
                            <a href="mailto:{{ $ticket->correo_solicitante }}?subject=Ticket {{ $ticket->folio }} - Seguimiento&body=Estimado/a {{ $ticket->nombre_solicitante }},

Referente a su ticket {{ $ticket->folio }}..."
                               class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center border border-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contactar Solicitante
                            </a>

                            <!-- Archivar Problema (solo para tickets cerrados) -->
                            @if($ticket->estado === 'cerrado')
                                <div class="mt-3">
                                    <a href="{{ route('archivo-problemas.create', $ticket->id) }}"
                                       class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center border border-gray-300">
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
        <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4" onclick="closeImageModal()" style="backdrop-filter: blur(2px);">
            <div class="relative max-w-5xl max-h-full" onclick="event.stopPropagation()">
                <button type="button" onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors duration-200 z-10" aria-label="Cerrar">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="" class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl bg-white" style="max-width: 90vw; max-height: 85vh;">
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-3 rounded-b-lg">
                    <p id="modalImageName" class="text-sm font-medium"></p>
                </div>
            </div>
        </div>

        <script>
            // Asegurar que el DOM esté cargado
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, initializing image modal functionality');
                
                // Verificar que el modal existe
                const modal = document.getElementById('imageModal');
                if (modal) {
                    console.log('Image modal found');
                } else {
                    console.error('Image modal not found!');
                }
            });

            // Variables globales para manejo de archivos
            let selectedFiles = [];

            // Funciones para manejo de imágenes del administrador
            document.getElementById('imagenes_admin').addEventListener('change', function(event) {
                const files = Array.from(event.target.files);
                selectedFiles = [...selectedFiles, ...files]; // Agregar archivos nuevos
                updateImagePreview();
            });

            function updateImagePreview() {
                const previewContainer = document.getElementById('imagePreviewAdmin');
                const uploadStatus = document.getElementById('uploadStatus');
                const fileCount = document.getElementById('fileCount');
                
                previewContainer.innerHTML = '';
                
                if (selectedFiles.length > 0) {
                    previewContainer.style.display = 'grid';
                    uploadStatus.classList.remove('hidden');
                    fileCount.textContent = selectedFiles.length;
                    
                    selectedFiles.forEach((file, index) => {
                        if (file && file.type && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imageContainer = document.createElement('div');
                                imageContainer.className = 'relative group';
                                imageContainer.dataset.index = index;
                                
                                imageContainer.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:border-blue-400 transition-all shadow-sm hover:shadow-md" onclick="openImageModal('${e.target.result}', 'Preview ${index + 1}')">
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" onclick="removePreviewImage(${index})" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 text-xs shadow-lg">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-1 left-1 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                                        ${Math.round(file.size / 1024)}KB
                                    </div>
                                `;
                                
                                previewContainer.appendChild(imageContainer);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    previewContainer.style.display = 'none';
                    uploadStatus.classList.add('hidden');
                }
                
                // Actualizar el input file con los archivos seleccionados
                updateFileInput();
            }

            function updateFileInput() {
                const fileInput = document.getElementById('imagenes_admin');
                const dt = new DataTransfer();
                
                selectedFiles.forEach(file => {
                    if (file) {
                        dt.items.add(file);
                    }
                });
                
                fileInput.files = dt.files;
            }

            function removePreviewImage(index) {
                selectedFiles.splice(index, 1); // Remover archivo del array
                updateImagePreview(); // Actualizar la preview
            }

            // Función de prueba para el modal
            function testModal() {
                console.log('Test modal clicked');
                openImageModal('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Imagen de Prueba');
            }

            let removedAdminImages = [];

            function togglePassword() {
                const passwordText = document.getElementById('password-text');
                const passwordHidden = document.getElementById('password-hidden');
                const toggleText = document.getElementById('toggle-text');
                
                if (passwordText.style.display === 'none') {
                    passwordText.style.display = 'inline';
                    passwordHidden.style.display = 'none';
                    toggleText.textContent = 'Ocultar';
                } else {
                    passwordText.style.display = 'none';
                    passwordHidden.style.display = 'inline';
                    toggleText.textContent = 'Mostrar';
                }
            }

            function removeExistingAdminImage(index) {
                // Agregar el índice a la lista de imágenes a remover
                removedAdminImages.push(index);
                
                // Ocultar la imagen visualmente
                event.target.closest('.relative').style.display = 'none';
                
                // Crear un campo hidden para enviar qué imágenes remover
                const form = document.querySelector('form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'removed_admin_images[]';
                hiddenInput.value = index;
                form.appendChild(hiddenInput);
            }

            function openImageModal(src, name) {
                console.log('🖼️ Opening modal with:', {src: typeof src === 'string' ? src.substring(0, 50) + '...' : src, name: name});
                
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                const modalImageName = document.getElementById('modalImageName');

                if (!modal) {
                    console.error('❌ Modal not found!');
                    return;
                }
                
                if (!modalImage) {
                    console.error('❌ Modal image not found!');
                    return;
                }

                let imageSrc = '';
                let imageAlt = name || 'Imagen del ticket';

                // Si src es un elemento img (cuando se pasa 'this' desde onclick)
                if (src && typeof src === 'object' && src.tagName === 'IMG') {
                    imageSrc = src.src;
                    imageAlt = src.alt || src.title || 'Imagen del ticket';
                    console.log('📸 Image element detected');
                } 
                // Si src es un string (URL de imagen)
                else if (typeof src === 'string') {
                    imageSrc = src;
                    imageAlt = name || 'Imagen del ticket';
                    console.log('🔗 String URL detected');
                } 
                else {
                    console.error('❌ Invalid src type:', typeof src);
                    return;
                }

                // Configurar imagen del modal
                modalImage.src = imageSrc;
                modalImage.alt = imageAlt;
                
                // Configurar nombre si existe
                if (modalImageName) {
                    modalImageName.textContent = imageAlt;
                }

                // Mostrar modal
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                console.log('✅ Modal displayed with image:', imageAlt);
            }

            function closeImageModal() {
                console.log('Closing modal'); // Debug
                const modal = document.getElementById('imageModal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }

            // Cerrar con tecla ESC (evento único)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('imageModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeImageModal();
                    }
                }
            });

            // Función expandImage eliminada - usamos solo el modal
        </script>
@endsection