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
                <div class="flex justify-between items-center h-16">
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

        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Agregar nuevo horario</h3>
                <form method="POST" action="{{ route('admin.maintenance.slots.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @csrf
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
                        <input type="date" id="date" name="date" value="{{ old('date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Guardar horario
                        </button>
                    </div>
                </form>
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
    </body>
</html>
