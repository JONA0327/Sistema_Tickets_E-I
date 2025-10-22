<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Editar Problema - E&I Sistema de Tickets</title>
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
                                    <h1 class="text-xl font-bold text-gray-900">Editar Problema</h1>
                                    <p class="text-sm text-gray-600">E&I - Tecnología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6" x-data="{ open: false }">
                        @include('components.nav-links', ['theme' => 'blue'])

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
                        <h2 class="text-2xl font-bold text-gray-900">
                            <svg class="w-6 h-6 inline-block mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Problema #{{ $problema->id }}
                        </h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('archivo-problemas.show', $problema->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('archivo-problemas.index') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Información del ticket relacionado -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <h3 class="text-lg font-medium text-blue-800 mb-2">📎 Ticket Relacionado</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-blue-700">Ticket:</span>
                                <span class="text-blue-600">#{{ $problema->ticket->id }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-700">Folio:</span>
                                <span class="text-blue-600">{{ $problema->ticket->folio }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de edición -->
                    <form action="{{ route('archivo-problemas.update', $problema->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Columna izquierda -->
                            <div class="space-y-4">
                                <div>
                                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                                    <select name="categoria" id="categoria" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Seleccionar categoría</option>
                                        <option value="hardware" {{ $problema->categoria == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                        <option value="software" {{ $problema->categoria == 'software' ? 'selected' : '' }}>Software</option>
                                        <option value="red" {{ $problema->categoria == 'red' ? 'selected' : '' }}>Red</option>
                                        <option value="impresora" {{ $problema->categoria == 'impresora' ? 'selected' : '' }}>Impresora</option>
                                        <option value="sistema" {{ $problema->categoria == 'sistema' ? 'selected' : '' }}>Sistema</option>
                                        <option value="otro" {{ $problema->categoria == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título del Problema</label>
                                    <input type="text" 
                                           name="titulo" 
                                           id="titulo" 
                                           value="{{ old('titulo', $problema->titulo) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           required>
                                </div>

                                <div>
                                    <label for="tipo_problema" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Problema</label>
                                    <select name="tipo_problema" id="tipo_problema" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="error" {{ $problema->tipo_problema == 'error' ? 'selected' : '' }}>Error</option>
                                        <option value="falla" {{ $problema->tipo_problema == 'falla' ? 'selected' : '' }}>Falla</option>
                                        <option value="configuracion" {{ $problema->tipo_problema == 'configuracion' ? 'selected' : '' }}>Configuración</option>
                                        <option value="mantenimiento" {{ $problema->tipo_problema == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                        <option value="actualizacion" {{ $problema->tipo_problema == 'actualizacion' ? 'selected' : '' }}>Actualización</option>
                                        <option value="otro" {{ $problema->tipo_problema == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="frecuencia" class="block text-sm font-medium text-gray-700 mb-1">Frecuencia</label>
                                    <select name="frecuencia" id="frecuencia" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Seleccionar frecuencia</option>
                                        <option value="unico" {{ $problema->frecuencia == 'unico' ? 'selected' : '' }}>Único</option>
                                        <option value="ocasional" {{ $problema->frecuencia == 'ocasional' ? 'selected' : '' }}>Ocasional</option>
                                        <option value="frecuente" {{ $problema->frecuencia == 'frecuente' ? 'selected' : '' }}>Frecuente</option>
                                        <option value="critico" {{ $problema->frecuencia == 'critico' ? 'selected' : '' }}>Crítico</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="palabras_clave" class="block text-sm font-medium text-gray-700 mb-1">Palabras Clave</label>
                                    <input type="text" 
                                           name="palabras_clave" 
                                           id="palabras_clave" 
                                           value="{{ old('palabras_clave', is_array($problema->palabras_clave) ? implode(', ', $problema->palabras_clave) : '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Separadas por comas">
                                    <p class="text-xs text-gray-500 mt-1">Ejemplo: servidor, red, conexión</p>
                                </div>
                            </div>

                            <!-- Columna derecha -->
                            <div class="space-y-4">
                                <div>
                                    <label for="descripcion_problema" class="block text-sm font-medium text-gray-700 mb-1">Descripción del Problema</label>
                                    <textarea name="descripcion_problema" 
                                              id="descripcion_problema" 
                                              rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              required>{{ old('descripcion_problema', $problema->descripcion_problema) }}</textarea>
                                </div>

                                <div>
                                    <label for="solucion" class="block text-sm font-medium text-gray-700 mb-1">Solución</label>
                                    <textarea name="solucion" 
                                              id="solucion" 
                                              rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              required>{{ old('solucion', $problema->solucion) }}</textarea>
                                </div>

                                <div>
                                    <label for="notas_adicionales" class="block text-sm font-medium text-gray-700 mb-1">Notas Adicionales (opcional)</label>
                                    <textarea name="notas_adicionales" 
                                              id="notas_adicionales" 
                                              rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Cualquier información adicional relevante">{{ old('notas_adicionales', $problema->notas_adicionales) }}</textarea>
                                </div>

                                <!-- Imágenes de solución existentes -->
                                @if($problema->solucion_imagenes && count($problema->solucion_imagenes) > 0)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes de Solución Actuales</label>
                                        <div class="grid grid-cols-2 gap-2 mb-3">
                                            @foreach($problema->solucion_imagenes as $imagen)
                                                <div class="relative border rounded-lg overflow-hidden">
                                                    <img src="{{ $imagen['data'] }}" 
                                                         alt="Figura {{ $imagen['figura'] }}"
                                                         class="w-full h-20 object-cover">
                                                    <div class="p-1 bg-gray-50">
                                                        <p class="text-xs font-medium text-gray-700">Figura {{ $imagen['figura'] }}</p>
                                                        <p class="text-xs text-gray-500 truncate">{{ $imagen['nombre'] }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label for="solucion_imagenes" class="block text-sm font-medium text-gray-700 mb-1">Nuevas Imágenes de Solución (opcional)</label>
                                    <input type="file" 
                                           name="solucion_imagenes[]" 
                                           id="solucion_imagenes" 
                                           multiple
                                           accept="image/*"
                                           class="w-full border border-gray-300 rounded-md p-2">
                                    <p class="text-xs text-gray-500 mt-1">Si subes nuevas imágenes, reemplazarán las existentes</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('archivo-problemas.show', $problema->id) }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Problema
                            </button>
                        </div>
                    </form>
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