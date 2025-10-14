<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $ticket ? 'Archivar Problema' : 'Nuevo Problema' }} - E&I Sistema de Tickets</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                                    <h1 class="text-xl font-bold text-gray-900">{{ $ticket ? 'Archivar Problema' : 'Nuevo Problema' }}</h1>
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

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('welcome') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🏠 Inicio</a>
                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">⚙️ Panel Admin</a>
                                @endif
                                <a href="{{ route('tickets.mis-tickets') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📋 Mis Tickets</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🚪 Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">
                            📝 {{ $ticket ? 'Archivar Problema del Ticket #' . $ticket->id : 'Nuevo Problema' }}
                        </h2>
                    <a href="{{ route('archivo-problemas.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                        ← Volver
                    </a>
                </div>

                @if($ticket)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Información del Ticket
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>Asunto:</strong> {{ $ticket->asunto }}</p>
                                    <p><strong>Usuario:</strong> {{ $ticket->user->name }}</p>
                                    <p><strong>Estado:</strong> 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ ucfirst($ticket->estado) }}
                                        </span>
                                    </p>
                                    <p><strong>Fecha de cierre:</strong> {{ $ticket->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('archivo-problemas.store') }}" class="space-y-6">
                    @csrf
                    
                    @if($ticket)
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    @else
                        <div>
                            <label for="ticket_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Ticket <span class="text-red-500">*</span>
                            </label>
                            <select name="ticket_id" 
                                    id="ticket_id" 
                                    required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona un ticket cerrado</option>
                                @php
                                    $ticketsCerrados = \App\Models\Ticket::where('estado', 'cerrado')
                                                                        ->with('user')
                                                                        ->orderBy('updated_at', 'desc')
                                                                        ->get();
                                @endphp
                                @foreach($ticketsCerrados as $ticketCerrado)
                                    <option value="{{ $ticketCerrado->id }}" {{ old('ticket_id') == $ticketCerrado->id ? 'selected' : '' }}>
                                        #{{ $ticketCerrado->id }} - {{ Str::limit($ticketCerrado->asunto, 50) }} ({{ $ticketCerrado->user->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('ticket_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <select name="categoria" 
                                    id="categoria" 
                                    required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categorias as $key => $value)
                                    <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tipo_problema" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Problema <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_problema" 
                                    id="tipo_problema" 
                                    required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona un tipo</option>
                                @foreach($tiposProblema as $key => $value)
                                    <option value="{{ $key }}" {{ old('tipo_problema') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_problema')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                            Título del Problema <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="titulo" 
                               id="titulo" 
                               value="{{ old('titulo', $ticket ? $ticket->asunto : '') }}" 
                               required 
                               maxlength="255"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Resumen del problema">
                        @error('titulo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcion_problema" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción del Problema <span class="text-red-500">*</span>
                        </label>
                        <textarea name="descripcion_problema" 
                                  id="descripcion_problema" 
                                  rows="4" 
                                  required 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Describe detalladamente el problema que se presentó">{{ old('descripcion_problema', $ticket ? $ticket->descripcion : '') }}</textarea>
                        @error('descripcion_problema')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="solucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Solución Implementada <span class="text-red-500">*</span>
                        </label>
                        <textarea name="solucion" 
                                  id="solucion" 
                                  rows="4" 
                                  required 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Describe los pasos que se siguieron para resolver el problema">{{ old('solucion') }}</textarea>
                        @error('solucion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="frecuencia" class="block text-sm font-medium text-gray-700 mb-2">
                                Frecuencia del Problema <span class="text-red-500">*</span>
                            </label>
                            <select name="frecuencia" 
                                    id="frecuencia" 
                                    required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona la frecuencia</option>
                                <option value="unico" {{ old('frecuencia') == 'unico' ? 'selected' : '' }}>
                                    🔵 Único - Primera vez que ocurre
                                </option>
                                <option value="ocasional" {{ old('frecuencia') == 'ocasional' ? 'selected' : '' }}>
                                    🟡 Ocasional - Ocurre de vez en cuando
                                </option>
                                <option value="frecuente" {{ old('frecuencia') == 'frecuente' ? 'selected' : '' }}>
                                    🟠 Frecuente - Ocurre regularmente
                                </option>
                                <option value="critico" {{ old('frecuencia') == 'critico' ? 'selected' : '' }}>
                                    🔴 Crítico - Problema recurrente grave
                                </option>
                            </select>
                            @error('frecuencia')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="palabras_clave" class="block text-sm font-medium text-gray-700 mb-2">
                                Palabras Clave <span class="text-gray-500">(opcional)</span>
                            </label>
                            <input type="text" 
                                   name="palabras_clave" 
                                   id="palabras_clave" 
                                   value="{{ old('palabras_clave') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="error, hardware, software (separadas por comas)">
                            <p class="text-xs text-gray-500 mt-1">Separa las palabras clave con comas. Ejemplo: error, hardware, red</p>
                            @error('palabras_clave')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="notas_adicionales" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas Adicionales <span class="text-gray-500">(opcional)</span>
                        </label>
                        <textarea name="notas_adicionales" 
                                  id="notas_adicionales" 
                                  rows="3" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Información adicional, prevención, recomendaciones...">{{ old('notas_adicionales') }}</textarea>
                        @error('notas_adicionales')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <a href="{{ route('archivo-problemas.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                            💾 Archivar Problema
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-completar título basado en el ticket seleccionado si no hay ticket predefinido
    const ticketSelect = document.getElementById('ticket_id');
    const tituloInput = document.getElementById('titulo');
    const descripcionTextarea = document.getElementById('descripcion_problema');
    
    if (ticketSelect) {
        ticketSelect.addEventListener('change', function() {
            if (this.value) {
                // Aquí podrías hacer una llamada AJAX para obtener los detalles del ticket
                // Por ahora, simplemente limpiamos los campos si no hay datos previos
                if (!tituloInput.value.trim()) {
                    const selectedOption = this.options[this.selectedIndex];
                    const ticketText = selectedOption.textContent;
                    const match = ticketText.match(/#\d+ - (.+) \(/);
                    if (match) {
                        tituloInput.value = match[1];
                    }
                }
            }
        });
    }
});
</script>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} E&I - Comercio Exterior, Logística y Tecnología. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </body>
</html>