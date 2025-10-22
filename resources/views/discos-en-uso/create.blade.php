<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrar Uso de Disco - E&I Sistema de Inventario</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-orange-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900">Registrar Uso de Disco</h1>
                                    <p class="text-sm text-gray-600">E&I - Gestión de Discos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center md:flex-row md:items-center md:justify-end gap-4 md:gap-6" x-data="{ open: false }">
                        @include('components.nav-links', ['theme' => 'orange'])

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button 
                                @click="open = !open" 
                                @click.away="open = false"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-orange-50 transition-colors duration-200">
                                <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
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
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="{{ route('inventario.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        📦 Inventario
                                    </a>
                                    <a href="{{ route('discos-en-uso.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        💾 Discos en Uso
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            🚪 Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('inventario.index') }}" class="hover:text-orange-600 transition-colors">📦 Inventario</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('discos-en-uso.index') }}" class="hover:text-orange-600 transition-colors">💾 Discos en Uso</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-800 font-medium">Registrar Uso</span>
            </div>

            <div class="bg-white rounded-lg shadow-lg border border-orange-200">
                <div class="px-6 py-4 border-b border-orange-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Registrar Disco en Uso
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Complete la información del disco que será instalado en una computadora</p>
                </div>

                <div class="p-6">
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <strong class="font-medium">Por favor, corrige los siguientes errores:</strong>
                            </div>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('discos-en-uso.store') }}" method="POST" x-data="{ 
                        selectedInventario: '{{ request('inventario_id') }}',
                        inventarios: {{ json_encode($inventarios->map(fn($inv) => ['id' => $inv->id, 'codigo' => $inv->codigo_inventario, 'modelo' => $inv->modelo, 'disponible' => $inv->cantidad_disponible > 0])) }}
                    }">
                        @csrf

                        <!-- Selección de Disco -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <label for="inventario_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        💾 Disco Duro a Instalar *
                                    </label>
                                    <select name="inventario_id" id="inventario_id" required
                                            x-model="selectedInventario"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        <option value="">Selecciona un disco disponible...</option>
                                        @foreach($inventarios as $inv)
                                            <option value="{{ $inv->id }}" 
                                                    {{ request('inventario_id') == $inv->id ? 'selected' : '' }}
                                                    {{ $inv->cantidad_disponible == 0 ? 'disabled' : '' }}>
                                                {{ $inv->codigo_inventario }} - {{ $inv->articulo }} {{ $inv->modelo }}
                                                {{ $inv->cantidad_disponible == 0 ? ' (No disponible)' : ' ✓' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('inventario_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Información de la Computadora -->
                                <div>
                                    <label for="computadora_ubicacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        🖥️ Computadora de Destino *
                                    </label>
                                    <input type="text" name="computadora_ubicacion" id="computadora_ubicacion" 
                                           value="{{ old('computadora_ubicacion') }}" required
                                           placeholder="Ej: PC-Oficina-001, Laptop-Desarrollo-005"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    @error('computadora_ubicacion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="area_computadora" class="block text-sm font-medium text-gray-700 mb-2">
                                        🏢 Área/Departamento
                                    </label>
                                    <input type="text" name="area_computadora" id="area_computadora" 
                                           value="{{ old('area_computadora') }}"
                                           placeholder="Ej: Desarrollo, Contabilidad, Gerencia"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    @error('area_computadora')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="razon_uso" class="block text-sm font-medium text-gray-700 mb-2">
                                        📋 Razón de Uso *
                                    </label>
                                    <textarea name="razon_uso" id="razon_uso" rows="4" required
                                              placeholder="Describe por qué se necesita instalar este disco en la computadora..."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('razon_uso') }}</textarea>
                                    @error('razon_uso')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Fechas -->
                                <div>
                                    <label for="fecha_instalacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        📅 Fecha de Instalación
                                    </label>
                                    <input type="date" name="fecha_instalacion" id="fecha_instalacion" 
                                           value="{{ old('fecha_instalacion', date('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    @error('fecha_instalacion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Información del Disco de Reemplazo -->
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-800 mb-3 flex items-center">
                                        🔄 Disco de Reemplazo (Opcional)
                                    </h4>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label for="disco_reemplazado" class="block text-xs font-medium text-gray-600 mb-1">
                                                💾 Disco que será reemplazado
                                            </label>
                                            <input type="text" name="disco_reemplazado" id="disco_reemplazado" 
                                                   value="{{ old('disco_reemplazado') }}"
                                                   placeholder="Ej: HDD 500GB Kingston"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        </div>

                                        <div>
                                            <label for="motivo_reemplazo" class="block text-xs font-medium text-gray-600 mb-1">
                                                📝 Motivo del reemplazo
                                            </label>
                                            <textarea name="motivo_reemplazo" id="motivo_reemplazo" rows="3"
                                                      placeholder="Ej: Disco anterior dañado, upgrade de capacidad"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('motivo_reemplazo') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observaciones adicionales -->
                                <div>
                                    <label for="observaciones_instalacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        📝 Observaciones de Instalación
                                    </label>
                                    <textarea name="observaciones_instalacion" id="observaciones_instalacion" rows="3"
                                              placeholder="Observaciones adicionales sobre la instalación..."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('observaciones_instalacion') }}</textarea>
                                    @error('observaciones_instalacion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información del Disco Seleccionado -->
                        <div x-show="selectedInventario" class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <h4 class="text-sm font-medium text-orange-800 mb-2">📋 Información del Disco Seleccionado</h4>
                            <template x-for="inventario in inventarios" :key="inventario.id">
                                <div x-show="inventario.id == selectedInventario" class="text-sm text-orange-700">
                                    <p><span class="font-medium">Código:</span> <span x-text="inventario.codigo"></span></p>
                                    <p><span class="font-medium">Modelo:</span> <span x-text="inventario.modelo"></span></p>
                                    <p><span class="font-medium">Disponible:</span> 
                                        <span x-text="inventario.disponible ? 'Sí ✓' : 'No disponible ✗'"
                                              :class="inventario.disponible ? 'text-green-700' : 'text-red-700'"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('discos-en-uso.index') }}" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                ❌ Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Registrar Uso de Disco
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Scripts adicionales -->
        <script>
            // Validación adicional en el cliente
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const submitBtn = form.querySelector('button[type="submit"]');
                
                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Registrando...';
                });
            });
        </script>
    </body>
</html>