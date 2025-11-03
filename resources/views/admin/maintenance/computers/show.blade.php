@extends('layouts.master')

@section('title', 'Detalles de ficha técnica - Panel Administrativo')

@section('content')
    <main class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Ficha técnica del equipo</h2>
                <p class="text-gray-600">Consulta la información registrada para este equipo de mantenimiento.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.maintenance.computers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver al historial
                </a>
                <a href="{{ route('admin.maintenance.computers.edit', $profile) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0h.01M7 5h.01M5 7h14M5 11h14M5 15h10" />
                    </svg>
                    Editar ficha
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Identificador</p>
                    <p class="text-lg font-bold text-blue-600 mt-1">{{ $profile->identifier ?? 'Sin asignar' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Marca y modelo</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ trim(($profile->brand ?? 'Marca no definida').' '.($profile->model ?? '')) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipo de disco</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $profile->disk_type ?? 'Sin registro' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Capacidad de RAM</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $profile->ram_capacity ?? 'Sin registro' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado de batería</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $profile->battery_status ? ucfirst(str_replace('_', ' ', $profile->battery_status)) : 'Sin registro' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Último mantenimiento</p>
                    <p class="text-sm text-gray-800 mt-1">
                        @if($profile->last_maintenance_at)
                            {{ $profile->last_maintenance_at->format('d/m/Y H:i') }}
                        @else
                            Sin registro
                        @endif
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Observaciones estéticas</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $profile->aesthetic_observations ?? 'Sin observaciones registradas.' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Componentes reemplazados</p>
                    @php
                        $componentLabels = collect($profile->replacement_components ?? [])->map(fn ($component) => $componentOptions[$component] ?? ucfirst(str_replace('_', ' ', $component)));
                    @endphp
                    @if($componentLabels->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($componentLabels as $label)
                                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-200">{{ $label }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-800 mt-1">No se registraron reemplazos.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado actual</h3>
            <div class="space-y-4 text-sm text-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <span class="font-medium text-gray-600">Condición de préstamo</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $profile->is_loaned ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $profile->is_loaned ? 'Prestado' : 'Disponible' }}
                    </span>
                </div>
                @if($profile->is_loaned)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Prestado a</p>
                            <p class="text-sm text-gray-800 mt-1">{{ $profile->loaned_to_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Correo de contacto</p>
                            <p class="text-sm text-gray-800 mt-1">{{ $profile->loaned_to_email }}</p>
                        </div>
                    </div>
                @endif
                @if($profile->last_ticket_id)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Último ticket relacionado</p>
                        <p class="text-sm text-blue-700 mt-1">
                            <a href="{{ route('admin.tickets.show', $profile->last_ticket_id) }}" class="hover:underline">
                                {{ optional($profile->ticket)->folio ?? 'Ticket #'.$profile->last_ticket_id }}
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
