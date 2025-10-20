<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mantenimientos Especiales - Panel Administrativo</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-emerald-50 via-blue-50 to-sky-100">
        @php
            use Illuminate\Support\Str;
        @endphp
        <header class="bg-white shadow-sm border-b border-emerald-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Gestión de Mantenimientos</h1>
                            <p class="text-sm text-gray-600">Agenda, seguimiento y archivo de equipos</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Panel Admin</a>
                        <a href="{{ route('admin.tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Tickets</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-700 font-semibold mb-2">Hubo algunos problemas:</p>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="bg-white border border-emerald-100 rounded-xl shadow-sm p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Configurar disponibilidad</h2>
                        <p class="text-gray-600">Define los días y horarios en los que se podrá agendar mantenimiento.</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $slots->count() }} horarios configurados
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-1 bg-emerald-50 border border-emerald-100 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-emerald-900 mb-4">Nuevo horario</h3>
                        <form method="POST" action="{{ route('admin.maintenance.slots.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="slot_date">Fecha</label>
                                <input type="date" id="slot_date" name="date" value="{{ old('date') }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="slot_start">Hora inicio</label>
                                    <input type="time" id="slot_start" name="start_time" value="{{ old('start_time') }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="slot_end">Hora fin (opcional)</label>
                                    <input type="time" id="slot_end" name="end_time" value="{{ old('end_time') }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="slot_capacity">Capacidad</label>
                                <input type="number" id="slot_capacity" name="capacity" min="1" max="20" value="{{ old('capacity', 1) }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                <p class="text-xs text-gray-500 mt-1">Número de equipos que se pueden agendar en este horario.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="slot_notes">Notas internas</label>
                                <textarea id="slot_notes" name="notes" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Ej. Requiere técnico especializado, tiempo estimado adicional, etc.">{{ old('notes') }}</textarea>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="slot_is_open" name="is_open" value="1" class="rounded text-emerald-600 focus:ring-emerald-500" {{ old('is_open', true) ? 'checked' : '' }}>
                                <label for="slot_is_open" class="text-sm text-gray-700">Horario activo para agendar</label>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-sm transition">
                                Crear horario
                            </button>
                        </form>
                    </div>

                    <div class="lg:col-span-2 space-y-6">
                        @forelse($slots as $slot)
                            <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                                <form method="POST" action="{{ route('admin.maintenance.slots.update', $slot) }}" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $slot->is_open ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                                {{ $slot->is_open ? 'Activo' : 'Cerrado' }}
                                            </span>
                                            <p class="text-lg font-semibold text-gray-900">
                                                {{ $slot->date->format('d/m/Y') }} · {{ $slot->display_time }}
                                            </p>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $slot->tickets_count }} tickets / {{ $slot->capacity }} capacidad
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Fecha</label>
                                            <input type="date" name="date" value="{{ $slot->date->format('Y-m-d') }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Inicio</label>
                                            <input type="time" name="start_time" value="{{ optional($slot->start_time)->format('H:i') }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Fin</label>
                                            <input type="time" name="end_time" value="{{ optional($slot->end_time)->format('H:i') }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Capacidad</label>
                                            <input type="number" name="capacity" min="1" max="20" value="{{ $slot->capacity }}" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Notas internas</label>
                                        <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Agrega observaciones para el equipo técnico.">{{ $slot->notes }}</textarea>
                                    </div>
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <label class="inline-flex items-center space-x-2 text-sm text-gray-700">
                                            <input type="checkbox" name="is_open" value="1" class="rounded text-emerald-600 focus:ring-emerald-500" {{ $slot->is_open ? 'checked' : '' }}>
                                            <span>Disponible para agendar</span>
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                                                Actualizar horario
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('admin.maintenance.slots.destroy', $slot) }}" onsubmit="return confirm('¿Eliminar este horario de mantenimiento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-200 text-red-600 hover:bg-red-50 text-sm font-medium rounded-lg transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="bg-white border border-dashed border-emerald-200 rounded-lg p-8 text-center text-gray-500">
                                <p>No hay horarios configurados aún. Usa el formulario para agregar la primera disponibilidad.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="bg-white border border-blue-100 rounded-xl shadow-sm p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Tickets de mantenimiento</h2>
                        <p class="text-gray-600">Visualiza los tickets especiales creados por los usuarios para su programación.</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-50 text-purple-700 border border-purple-200">
                        {{ $tickets->count() }} tickets
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($tickets as $ticket)
                        <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-gray-500">Folio: {{ $ticket->folio }}</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $ticket->estado_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $ticket->nombre_solicitante }}</h3>
                                <p class="text-sm text-gray-600">{{ $ticket->correo_solicitante }}</p>
                                <p class="text-sm text-gray-500 mt-2">Solicitado el {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-700 space-y-2">
                                <p><span class="font-medium text-gray-900">Descripción:</span> {{ Str::limit($ticket->descripcion_problema, 160) }}</p>
                                @if($ticket->maintenance_details)
                                    <p><span class="font-medium text-gray-900">Detalles extra:</span> {{ Str::limit($ticket->maintenance_details, 160) }}</p>
                                @endif
                                @if($ticket->maintenanceSlot)
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-emerald-100 text-emerald-700 font-medium">{{ $ticket->maintenanceSlot->date->format('d/m/Y') }}</span>
                                        <span>{{ $ticket->maintenanceSlot->display_time }}</span>
                                    </div>
                                @elseif($ticket->maintenance_date)
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-emerald-100 text-emerald-700 font-medium">{{ $ticket->maintenance_date->format('d/m/Y') }}</span>
                                        <span>{{ optional($ticket->maintenance_time)->format('H:i') }}</span>
                                    </div>
                                @else
                                    <p class="text-xs text-red-500">Sin horario asignado.</p>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                                    Ver detalle
                                </a>
                                <div class="text-xs text-gray-500 text-right">
                                    <p>Tipo: Ticket especial</p>
                                    <p>Imágenes: {{ $ticket->imagenes ? count($ticket->imagenes) : 0 }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white border border-dashed border-blue-200 rounded-lg p-8 text-center text-gray-500">
                            <p>No hay tickets de mantenimiento registrados.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="bg-white border border-purple-100 rounded-xl shadow-sm p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Archivo de equipos</h2>
                        <p class="text-gray-600">Consulta el historial y controla los préstamos posteriores al mantenimiento.</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $records->count() }} equipos registrados
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Historial</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Componentes</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Préstamo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($records as $record)
                                <tr class="align-top">
                                    <td class="px-4 py-4 text-sm text-gray-700 space-y-2">
                                        <p class="font-semibold text-gray-900">{{ $record->equipo_marca }} {{ $record->equipo_modelo }}</p>
                                        <p class="text-xs text-gray-500">Solicitante: {{ $record->usuario_nombre }} ({{ $record->usuario_correo }})</p>
                                        <p class="text-xs text-gray-500">Ticket: <a class="text-blue-600 hover:text-blue-800" href="{{ route('admin.tickets.show', $record->ticket) }}">{{ $record->ticket->folio }}</a></p>
                                        @if($record->mantenimiento_programado)
                                            <p class="text-xs text-gray-500">Programado: {{ $record->mantenimiento_programado->format('d/m/Y H:i') }}</p>
                                        @endif
                                        @if($record->equipo_tipo_disco || $record->equipo_ram_capacidad)
                                            <p class="text-xs text-gray-500">Disco: {{ $record->equipo_tipo_disco ?? 'N/A' }} · RAM: {{ $record->equipo_ram_capacidad ?? 'N/A' }}</p>
                                        @endif
                                        @if($record->equipo_bateria_estado)
                                            <p class="text-xs">
                                                <span class="font-medium">Batería:</span>
                                                <span class="uppercase tracking-wide text-{{ $record->equipo_bateria_estado === 'funcional' ? 'emerald-600' : ($record->equipo_bateria_estado === 'parcialmente_funcional' ? 'amber-600' : 'red-600') }}">
                                                    {{ str_replace('_', ' ', ucfirst($record->equipo_bateria_estado)) }}
                                                </span>
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 space-y-2">
                                        @if($record->maintenance_cierre_observaciones)
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600">Observaciones de cierre</p>
                                                <p class="text-xs text-gray-600">{{ $record->maintenance_cierre_observaciones }}</p>
                                            </div>
                                        @endif
                                        @if($record->maintenance_reporte)
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600">Reporte técnico</p>
                                                <p class="text-xs text-gray-600 whitespace-pre-line">{{ $record->maintenance_reporte }}</p>
                                            </div>
                                        @endif
                                        @if($record->equipo_observaciones_esteticas)
                                            <div>
                                                <p class="text-xs font-semibold text-gray-600">Estética</p>
                                                <p class="text-xs text-gray-600">{{ $record->equipo_observaciones_esteticas }}</p>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        @if($record->maintenance_componentes_reemplazo && count($record->maintenance_componentes_reemplazo))
                                            <ul class="list-disc list-inside text-xs space-y-1">
                                                @foreach($record->maintenance_componentes_reemplazo as $componente)
                                                    <li>{{ Str::title(str_replace('_', ' ', $componente)) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-xs text-gray-500">Sin reemplazos registrados.</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        <p class="text-xs font-semibold {{ $record->prestado ? 'text-red-600' : 'text-emerald-600' }}">
                                            {{ $record->prestado ? 'Equipo prestado' : 'Disponible' }}
                                        </p>
                                        @if($record->prestado && $record->prestado_a_nombre)
                                            <p class="text-xs text-gray-500 mt-1">A: {{ $record->prestado_a_nombre }} ({{ $record->prestado_a_correo }})</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        <form method="POST" action="{{ route('admin.maintenance.equipment.loan', $record) }}" class="space-y-3">
                                            @csrf
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Estado del préstamo</label>
                                                <select name="prestado" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                                                    <option value="0" {{ !$record->prestado ? 'selected' : '' }}>Disponible</option>
                                                    <option value="1" {{ $record->prestado ? 'selected' : '' }}>Prestado</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre de quien lo recibe</label>
                                                <input type="text" name="prestado_a_nombre" value="{{ old('prestado_a_nombre', $record->prestado_a_nombre ?? $record->usuario_nombre) }}" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Correo de contacto</label>
                                                <input type="email" name="prestado_a_correo" value="{{ old('prestado_a_correo', $record->prestado_a_correo ?? $record->usuario_correo) }}" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                                            </div>
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                                                Guardar cambios
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Aún no hay equipos archivados. Cierra un ticket de mantenimiento para generar el expediente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </body>
</html>
