<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Expedientes de Equipos - Panel Administrativo</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
        @include('layouts.navigation')

        <header class="bg-white border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Mantenimiento</p>
                        <h1 class="text-2xl font-bold text-gray-900 leading-tight">Expedientes de Equipos</h1>
                        <p class="mt-1 text-sm text-gray-600">Consulta y gestiona el historial de mantenimiento de cada equipo.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('admin.maintenance.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3h8v4m-9 4h10m-9 4h8m-7 4h4" />
                            </svg>
                            Horarios
                        </a>
                        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-8 4h10a2 2 0 002-2V6a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293L11.414 2.586A2 2 0 0010 2H6a2 2 0 00-2 2v16a2 2 0 002 2z" />
                            </svg>
                            Tickets
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Panel Admin
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Historial de equipos</h2>
                <p class="text-gray-600">Mantén un registro centralizado de los equipos intervenidos y su estado de préstamo.</p>
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

            <div class="bg-white border border-blue-100 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Equipos registrados ({{ $profiles->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identificador</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalles técnicos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Último mantenimiento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Préstamo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($profiles as $profile)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-blue-600">{{ $profile->identifier ?? 'Sin asignar' }}</div>
                                        @if($profile->last_ticket_id)
                                            <div class="text-xs text-gray-500">Ticket: <a href="{{ route('admin.tickets.show', $profile->last_ticket_id) }}" class="text-blue-600 hover:text-blue-800">{{ optional($profile->ticket)->folio ?? $profile->last_ticket_id }}</a></div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $profile->brand ?? 'Marca no definida' }} {{ $profile->model }}</div>
                                        <div class="text-xs text-gray-500">Batería: {{ $profile->battery_status ? ucfirst(str_replace('_', ' ', $profile->battery_status)) : 'Sin registrar' }}</div>
                                        @if($profile->aesthetic_observations)
                                            <div class="mt-1 text-xs text-gray-500">Observaciones: {{ $profile->aesthetic_observations }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div>Disco: <span class="font-medium text-gray-900">{{ $profile->disk_type ?? 'N/A' }}</span></div>
                                        <div>RAM: <span class="font-medium text-gray-900">{{ $profile->ram_capacity ?? 'N/A' }}</span></div>
                                        @if($profile->replacement_components)
                                            <div class="mt-1 text-xs text-gray-500">Componentes reemplazados: {{ collect($profile->replacement_components)->map(fn($component) => ucfirst(str_replace('_', ' ', $component)))->implode(', ') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if($profile->last_maintenance_at)
                                            {{ $profile->last_maintenance_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400">Sin registro</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="font-medium {{ $profile->is_loaned ? 'text-green-600' : 'text-gray-500' }}">
                                            {{ $profile->is_loaned ? 'Prestado' : 'Disponible' }}
                                        </div>
                                        @if($profile->is_loaned)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $profile->loaned_to_name }}<br>
                                                {{ $profile->loaned_to_email }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <form method="POST" action="{{ route('admin.maintenance.computers.update-loan', $profile) }}" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <label class="flex items-center text-xs text-gray-600">
                                                <input type="checkbox" name="is_loaned" value="1" class="mr-2 rounded border-gray-300 text-green-600 focus:ring-green-500" {{ $profile->is_loaned ? 'checked' : '' }}>
                                                Marcar como prestado
                                            </label>
                                            <input type="text" name="loaned_to_name" value="{{ old('loaned_to_name', $profile->loaned_to_name) }}" placeholder="Nombre de la persona" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <input type="email" name="loaned_to_email" value="{{ old('loaned_to_email', $profile->loaned_to_email) }}" placeholder="Correo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">Actualizar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        No hay expedientes registrados aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>
