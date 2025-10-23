<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Editar Artículo - E&I Sistema de Inventario</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-green-50 to-green-100">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-green-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900">Editar Artículo</h1>
                                    <p class="text-sm text-gray-600">E&I - Inventario</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center md:flex-row md:items-center md:justify-end gap-4 md:gap-6" x-data="{ open: false }">
                        @include('components.nav-links', ['theme' => 'green'])

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button 
                                @click="open = !open" 
                                @click.away="open = false"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
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
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">⚙️ Panel Admin</a>
                                <a href="{{ route('inventario.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📦 Inventario</a>
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
        <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <svg class="w-6 h-6 inline-block mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Artículo #{{ $inventario->id }}
                        </h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('inventario.show', $inventario) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('inventario.index') }}" 
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

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form -->
                    @php
                        $esMultiple = str_contains($inventario->observaciones ?? '', '--- DETALLES POR UNIDAD ---');
                        $observacionesSeparadas = $inventario->observaciones_separadas;
                        $unidades = [];
                        $oldUnits = old('unidades');

                        if (is_array($oldUnits) && count($oldUnits) > 0) {
                            foreach ($oldUnits as $index => $unidadOld) {
                                $unidades[] = [
                                    'numero' => $unidadOld['numero'] ?? $index + 1,
                                    'estado' => $unidadOld['estado'] ?? 'nuevo',
                                    'color_primario' => $unidadOld['color_primario'] ?? '',
                                    'color_secundario' => $unidadOld['color_secundario'] ?? '',
                                    'observaciones' => $unidadOld['observaciones'] ?? ''
                                ];
                            }
                        } elseif ($esMultiple) {
                            $detalles = $observacionesSeparadas['detalles'] ?? [];

                            foreach ($detalles as $index => $linea) {
                                if (str_contains($linea, 'UNIDAD')) {
                                    $partes_unidad = explode(' | ', $linea);
                                    $unidad = [
                                        'numero' => trim(str_replace('UNIDAD', '', $partes_unidad[0] ?? '')) ?: ($index + 1),
                                        'estado' => 'nuevo',
                                        'color_primario' => '',
                                        'color_secundario' => '',
                                        'observaciones' => ''
                                    ];

                                    foreach ($partes_unidad as $parte) {
                                        if (str_contains($parte, 'Estado:')) {
                                            $unidad['estado'] = strtolower(trim(str_replace('Estado:', '', $parte)));
                                        } elseif (str_contains($parte, 'Color primario:')) {
                                            $unidad['color_primario'] = trim(str_replace('Color primario:', '', $parte));
                                        } elseif (str_contains($parte, 'Color secundario:')) {
                                            $unidad['color_secundario'] = trim(str_replace('Color secundario:', '', $parte));
                                        } elseif (str_contains($parte, 'Color:')) {
                                            $unidad['color_primario'] = trim(str_replace('Color:', '', $parte));
                                        } elseif (str_contains($parte, 'Notas:')) {
                                            $unidad['observaciones'] = trim(str_replace('Notas:', '', $parte));
                                        }
                                    }
                                    $unidades[] = $unidad;
                                }
                            }
                        }
                    @endphp

                    <form action="{{ route('inventario.update', $inventario) }}" method="POST" enctype="multipart/form-data"
                          x-data="{
                              categoria: '{{ $inventario->categoria }}',
                              esMultiple: {{ $esMultiple ? 'true' : 'false' }},
                              unidades: @js($unidades),
                              colorPrimario: @js(old('color_primario', $inventario->color_primario)),
                              colorSecundario: @js(old('color_secundario', $inventario->color_secundario))
                          }">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Columna izquierda -->
                            <div class="space-y-4">
                                <div>
                                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                                    <select name="categoria" 
                                            id="categoria" 
                                            x-model="categoria"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                                            required>
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categorias as $key => $label)
                                            <option value="{{ $key }}" {{ (old('categoria', $inventario->categoria) == $key) ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="articulo" class="block text-sm font-medium text-gray-700 mb-1">Artículo *</label>
                                    <input type="text" 
                                           name="articulo" 
                                           id="articulo" 
                                           value="{{ old('articulo', $inventario->articulo) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                           required>
                                </div>

                                <div>
                                    <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo *</label>
                                    <input type="text"
                                           name="modelo"
                                           id="modelo"
                                           value="{{ old('modelo', $inventario->modelo) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                           required>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Color primario</label>
                                        <input type="hidden" name="color_primario" x-model="colorPrimario">
                                        <div class="flex items-center gap-3">
                                            <input type="color"
                                                   class="h-10 w-16 border border-gray-300 rounded cursor-pointer"
                                                   x-bind:value="colorPrimario || '#000000'"
                                                   @input="colorPrimario = $event.target.value">
                                            <button type="button"
                                                    class="text-xs text-gray-600 hover:text-gray-800"
                                                    @click="colorPrimario = ''">
                                                Sin color
                                            </button>
                                        </div>
                                        <p class="text-[11px] text-gray-500 mt-1">
                                            <template x-if="colorPrimario">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                                                    <span class="w-3 h-3 rounded-full border border-gray-300 mr-2"
                                                          x-bind:style="colorPrimario ? `background-color: ${colorPrimario}` : ''"></span>
                                                    <span x-text="colorPrimario.toUpperCase()"></span>
                                                </span>
                                            </template>
                                            <template x-if="!colorPrimario">
                                                <span>Sin color seleccionado</span>
                                            </template>
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Color secundario</label>
                                        <input type="hidden" name="color_secundario" x-model="colorSecundario">
                                        <div class="flex items-center gap-3">
                                            <input type="color"
                                                   class="h-10 w-16 border border-gray-300 rounded cursor-pointer"
                                                   x-bind:value="colorSecundario || '#000000'"
                                                   @input="colorSecundario = $event.target.value">
                                            <button type="button"
                                                    class="text-xs text-gray-600 hover:text-gray-800"
                                                    @click="colorSecundario = ''">
                                                Sin color
                                            </button>
                                        </div>
                                        <p class="text-[11px] text-gray-500 mt-1">
                                            <template x-if="colorSecundario">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                                                    <span class="w-3 h-3 rounded-full border border-gray-300 mr-2"
                                                          x-bind:style="colorSecundario ? `background-color: ${colorSecundario}` : ''"></span>
                                                    <span x-text="colorSecundario.toUpperCase()"></span>
                                                </span>
                                            </template>
                                            <template x-if="!colorSecundario">
                                                <span>Sin color seleccionado</span>
                                            </template>
                                        </p>
                                    </div>
                                </div>

                                <!-- Campos para unidad única -->
                                <div x-show="!esMultiple" class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">Cantidad *</label>
                                        <input type="number" 
                                               name="cantidad" 
                                               id="cantidad" 
                                               value="{{ old('cantidad', $inventario->cantidad) }}"
                                               min="1"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        @if($inventario->cantidad_disponible != $inventario->cantidad)
                                            <p class="text-xs text-amber-600 mt-1">
                                                💡 Disponible: {{ $inventario->cantidad_disponible }}/{{ $inventario->cantidad }}
                                            </p>
                                        @endif
                                    </div>

                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                                        <select name="estado" id="estado" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                required>
                                            <option value="">Seleccionar estado</option>
                                            @foreach($estados as $key => $label)
                                                <option value="{{ $key }}" {{ (old('estado', $inventario->estado) == $key) ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Campos ocultos para garantizar que siempre se envíen los datos básicos cuando es múltiple -->
                                <template x-if="esMultiple">
                                    <div>
                                        <input type="hidden" name="cantidad" :value="unidades.length">
                                        <input type="hidden" name="estado" x-bind:value="
                                            (() => {
                                                const estados = unidades.map(u => u.estado);
                                                if (estados.includes('dañado')) return 'dañado';
                                                if (estados.includes('usado')) return 'usado';
                                                return 'nuevo';
                                            })()
                                        ">
                                    </div>
                                </template>

                                <!-- Campos para múltiples unidades -->
                                <div x-show="esMultiple" x-transition class="space-y-4">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h4 class="text-sm font-medium text-blue-800 mb-1">🏷️ Editar Unidades Individuales</h4>
                                                <p class="text-xs text-blue-600">
                                                    Modifica el estado, color y observaciones específicas de cada unidad.
                                                </p>
                                            </div>
                                            <div class="text-sm font-medium text-blue-700">
                                                Total: <span x-text="unidades.length"></span> unidades
                                            </div>
                                        </div>

                                        <div class="space-y-3 max-h-64 overflow-y-auto">
                                            <template x-for="(unidad, index) in unidades" :key="index">
                                                <div class="bg-white border border-gray-200 rounded-lg p-3">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <h5 class="text-sm font-medium text-gray-700">
                                                            🏷️ Unidad <span x-text="unidad.numero"></span>
                                                        </h5>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                                        <!-- Estado -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Estado *</label>
                                                            <select x-model="unidad.estado"
                                                                    :name="'unidades[' + index + '][estado]'"
                                                                    class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                                    required>
                                                                @foreach($estados as $key => $label)
                                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Color primario -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Color primario</label>
                                                            <input type="hidden" :name="'unidades[' + index + '][color_primario]'" x-model="unidad.color_primario">
                                                            <div class="flex items-center gap-2">
                                                                <input type="color"
                                                                       class="h-9 w-14 border border-gray-300 rounded cursor-pointer"
                                                                       x-bind:value="unidad.color_primario || colorPrimario || '#000000'"
                                                                       @input="unidad.color_primario = $event.target.value">
                                                                <button type="button"
                                                                        class="text-[11px] text-gray-600 hover:text-gray-800"
                                                                        @click="unidad.color_primario = ''">
                                                                    Sin color
                                                                </button>
                                                            </div>
                                                            <p class="text-[11px] text-gray-500 mt-1">
                                                                <template x-if="unidad.color_primario">
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                                                                        <span class="w-3 h-3 rounded-full border border-gray-300 mr-1"
                                                                              x-bind:style="unidad.color_primario ? `background-color: ${unidad.color_primario}` : ''"></span>
                                                                        <span x-text="unidad.color_primario.toUpperCase()"></span>
                                                                    </span>
                                                                </template>
                                                                <template x-if="!unidad.color_primario">
                                                                    <span>Sin color</span>
                                                                </template>
                                                            </p>
                                                        </div>

                                                        <!-- Color secundario -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Color secundario</label>
                                                            <input type="hidden" :name="'unidades[' + index + '][color_secundario]'" x-model="unidad.color_secundario">
                                                            <div class="flex items-center gap-2">
                                                                <input type="color"
                                                                       class="h-9 w-14 border border-gray-300 rounded cursor-pointer"
                                                                       x-bind:value="unidad.color_secundario || colorSecundario || '#000000'"
                                                                       @input="unidad.color_secundario = $event.target.value">
                                                                <button type="button"
                                                                        class="text-[11px] text-gray-600 hover:text-gray-800"
                                                                        @click="unidad.color_secundario = ''">
                                                                    Sin color
                                                                </button>
                                                            </div>
                                                            <p class="text-[11px] text-gray-500 mt-1">
                                                                <template x-if="unidad.color_secundario">
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                                                                        <span class="w-3 h-3 rounded-full border border-gray-300 mr-1"
                                                                              x-bind:style="unidad.color_secundario ? `background-color: ${unidad.color_secundario}` : ''"></span>
                                                                        <span x-text="unidad.color_secundario.toUpperCase()"></span>
                                                                    </span>
                                                                </template>
                                                                <template x-if="!unidad.color_secundario">
                                                                    <span>Sin color</span>
                                                                </template>
                                                            </p>
                                                        </div>

                                                        <!-- Observaciones específicas -->
                                                        <div class="md:col-span-2">
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Observaciones específicas</label>
                                                            <textarea x-model="unidad.observaciones"
                                                                      :name="'unidades[' + index + '][observaciones]'"
                                                                      rows="2"
                                                                      class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                                      placeholder="Detalles únicos de esta unidad..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="mt-3 p-2 bg-amber-50 border border-amber-200 rounded text-xs text-amber-700">
                                            <strong>💡 Información:</strong> Los cambios se aplicarán manteniendo la estructura de múltiples unidades.
                                        </div>
                                    </div>

                                    <!-- Campo oculto para indicar que es múltiple -->
                                    <input type="hidden" name="es_multiple" value="1">
                                </div>

                                <!-- Campos específicos para computadoras -->
                                <div x-show="categoria === 'computadoras'" x-transition class="space-y-4">
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                        <h4 class="text-md font-medium text-blue-800 mb-3">💻 Información de Computadora</h4>
                                        
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label for="password_computadora" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                                                <input type="text" 
                                                       name="password_computadora" 
                                                       id="password_computadora" 
                                                       value="{{ old('password_computadora', $inventario->password_computadora) }}"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                       placeholder="Contraseña de acceso">
                                            </div>

                                            <div>
                                                <label for="anos_uso" class="block text-sm font-medium text-gray-700 mb-1">Años de Uso</label>
                                                <input type="number" 
                                                       name="anos_uso" 
                                                       id="anos_uso" 
                                                       value="{{ old('anos_uso', $inventario->anos_uso) }}"
                                                       min="0"
                                                       max="50"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                       placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos específicos para discos duros -->
                                <div x-show="categoria === 'discos_duros'" x-transition class="space-y-4">
                                    <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                                        <h4 class="text-md font-medium text-orange-800 mb-3">💾 Información del Disco Duro</h4>
                                        
                                        <div class="space-y-4">
                                            <!-- Checkbox para marcar si tiene información -->
                                            <div class="flex items-start space-x-3">
                                                <input type="checkbox" 
                                                       name="tiene_informacion" 
                                                       id="tiene_informacion"
                                                       value="1"
                                                       x-model="tieneInfo"
                                                       class="mt-1 rounded border-gray-300 text-orange-600 focus:border-orange-500 focus:ring-orange-500">
                                                <div class="flex-1">
                                                    <label for="tiene_informacion" class="text-sm font-medium text-gray-700">
                                                        🔒 Este disco contiene información importante
                                                    </label>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Marca esta opción si el disco tiene datos, documentos o información que requiere protección especial
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Campos que aparecen cuando se marca "tiene información" -->
                                            <div x-data="{ 
                                                tieneInfo: {{ old('tiene_informacion', $inventario->tiene_informacion) ? 'true' : 'false' }},
                                                bloqueado: {{ old('bloqueado_prestamo', $inventario->bloqueado_prestamo) ? 'true' : 'false' }}
                                            }">
                                                
                                                <div x-show="tieneInfo" x-transition class="space-y-4 mt-4 p-3 bg-white rounded border border-orange-200">
                                                    <!-- Nivel de confidencialidad -->
                                                    <div>
                                                        <label for="nivel_confidencialidad" class="block text-sm font-medium text-gray-700 mb-1">
                                                            🛡️ Nivel de Confidencialidad
                                                        </label>
                                                        <select name="nivel_confidencialidad" 
                                                                id="nivel_confidencialidad"
                                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                                            <option value="">Seleccionar nivel</option>
                                                            @foreach(\App\Models\Inventario::getNivelesConfidencialidad() as $key => $label)
                                                                <option value="{{ $key }}" 
                                                                        {{ (old('nivel_confidencialidad', $inventario->nivel_confidencialidad) == $key) ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Descripción del contenido -->
                                                    <div>
                                                        <label for="informacion_contenido" class="block text-sm font-medium text-gray-700 mb-1">
                                                            📄 Descripción del Contenido
                                                        </label>
                                                        <textarea name="informacion_contenido" 
                                                                  id="informacion_contenido"
                                                                  rows="3"
                                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                                                  placeholder="Describe qué tipo de información contiene (ej: Documentos financieros, bases de datos de clientes, código fuente, etc.)">{{ old('informacion_contenido', $inventario->informacion_contenido) }}</textarea>
                                                    </div>

                                                    <!-- Bloquear préstamos -->
                                                    <div class="flex items-start space-x-3">
                                                        <input type="checkbox" 
                                                               name="bloqueado_prestamo" 
                                                               id="bloqueado_prestamo"
                                                               value="1"
                                                               x-model="bloqueado"
                                                               class="mt-1 rounded border-gray-300 text-red-600 focus:border-red-500 focus:ring-red-500">
                                                        <div class="flex-1">
                                                            <label for="bloqueado_prestamo" class="text-sm font-medium text-gray-700">
                                                                🚫 Bloquear préstamos
                                                            </label>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Impide que este disco sea prestado a usuarios
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Razón del bloqueo -->
                                                    <div x-show="bloqueado" x-transition>
                                                        <div x-show="bloqueado" x-transition>
                                                            <label for="razon_bloqueo" class="block text-sm font-medium text-gray-700 mb-1">
                                                                📋 Razón del Bloqueo
                                                            </label>
                                                            <textarea name="razon_bloqueo" 
                                                                      id="razon_bloqueo"
                                                                      rows="2"
                                                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                                                      placeholder="Explica por qué este disco no puede ser prestado (ej: Contiene información confidencial de la empresa)">{{ old('razon_bloqueo', $inventario->razon_bloqueo) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha -->
                            <div class="space-y-4">
                                <div>
                                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                                        <span x-show="!esMultiple">Observaciones</span>
                                        <span x-show="esMultiple">Observaciones Generales (opcionales)</span>
                                    </label>
                                    @php
                                        $observacionesGenerales = $esMultiple ?
                                            ($observacionesSeparadas['general'] ?? '') :
                                            $inventario->observaciones;
                                    @endphp
                                    <textarea name="observaciones" 
                                              id="observaciones" 
                                              rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                              x-bind:placeholder="esMultiple ? 
                                                  'Información que se aplica a todas las unidades (ej: marca, año de compra, etc.)' : 
                                                  'Detalles adicionales, condiciones especiales, etc.'">{{ old('observaciones', $observacionesGenerales) }}</textarea>
                                    <p x-show="esMultiple" class="text-xs text-gray-500 mt-1">
                                        💡 Las observaciones específicas de cada unidad se configuran en la sección de arriba
                                    </p>
                                </div>

                                <!-- Imágenes actuales -->
                                @if($inventario->imagenes && count($inventario->imagenes) > 0)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Imágenes Actuales</label>
                                        <div class="grid grid-cols-3 gap-2 mb-4">
                                            @foreach($inventario->imagenes as $index => $imagen)
                                                <div class="relative border rounded-lg overflow-hidden bg-gray-50" x-data="{ showConfirm: false }">
                                                    <img src="{{ is_array($imagen) && isset($imagen['data']) ? $imagen['data'] : 'data:image/jpeg;base64,' . $imagen }}" class="w-full h-20 object-cover" />
                                                    <div class="absolute top-1 right-1">
                                                        <button type="button" 
                                                                @click="showConfirm = true"
                                                                class="bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                                                            ×
                                                        </button>
                                                    </div>
                                                    <div class="text-xs text-center p-1 bg-gray-100">
                                                        <span class="font-medium">#{{ $index + 1 }}</span>
                                                    </div>

                                                    <!-- Confirmación de eliminación -->
                                                    <div x-show="showConfirm" 
                                                         x-transition
                                                         class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center">
                                                        <div class="bg-white p-2 rounded text-center">
                                                            <p class="text-xs mb-2">¿Eliminar?</p>
                                                            <div class="space-x-1">
                                                                <button type="button"
                                                                        @click="showConfirm = false"
                                                                        class="bg-gray-300 hover:bg-gray-400 px-2 py-1 rounded text-xs">
                                                                    No
                                                                </button>
                                                                <button type="button"
                                                                        onclick="eliminarImagen({{ $inventario->id }}, {{ $index }})"
                                                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                                                    Sí
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label for="nuevas_imagenes" class="block text-sm font-medium text-gray-700 mb-1">Agregar Nuevas Imágenes</label>
                                    <input type="file" 
                                           name="imagenes[]" 
                                           id="nuevas_imagenes" 
                                           multiple
                                           accept="image/jpeg,image/jpg,image/png"
                                           class="w-full border border-gray-300 rounded-md p-2 focus:border-green-500 focus:ring-green-500">
                                    <div class="mt-1 flex items-center space-x-4">
                                        <p class="text-xs text-gray-500">
                                            <strong>📁 Formatos:</strong> Solo JPG, JPEG y PNG. <strong>💡 Tip:</strong> Mantén <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Ctrl</kbd> presionado para seleccionar múltiples archivos
                                        </p>
                                        <span id="image_count" class="text-xs text-green-600 font-medium"></span>
                                    </div>
                                    <div id="image_preview" class="mt-3 grid grid-cols-3 gap-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <a href="{{ route('inventario.show', $inventario) }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Artículo
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

        <script>
            // Preview de nuevas imágenes
            document.getElementById('nuevas_imagenes').addEventListener('change', function(e) {
                const previewContainer = document.getElementById('image_preview');
                const counterSpan = document.getElementById('image_count');
                previewContainer.innerHTML = '';
                
                const files = Array.from(e.target.files);
                
                // Validar tipos de archivo
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                const invalidFiles = files.filter(file => !validTypes.includes(file.type));
                
                if (invalidFiles.length > 0) {
                    alert('❌ Solo se permiten archivos JPG, JPEG y PNG.\n\nArchivos no válidos detectados:\n' + 
                          invalidFiles.map(f => f.name).join('\n'));
                    e.target.value = '';
                    counterSpan.textContent = '';
                    return;
                }
                
                // Actualizar contador
                if (files.length > 0) {
                    counterSpan.textContent = `📸 ${files.length} nueva${files.length > 1 ? 's' : ''} imagen${files.length > 1 ? 'es' : ''} seleccionada${files.length > 1 ? 's' : ''}`;
                    counterSpan.className = 'text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded';
                } else {
                    counterSpan.textContent = '';
                    counterSpan.className = 'text-xs text-green-600 font-medium';
                }
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'relative border rounded-lg overflow-hidden bg-blue-50';
                            previewDiv.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-20 object-cover" />
                                <div class="text-xs text-center p-1 bg-blue-100">
                                    <span class="font-medium text-blue-700">Nuevo #${index + 1}</span>
                                </div>
                                <div class="text-xs text-gray-600 truncate px-1" title="${file.name}">${file.name}</div>
                            `;
                            previewContainer.appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

            // Función para eliminar imagen existente
            function eliminarImagen(inventarioId, indiceImagen) {
                if (confirm('¿Estás seguro de eliminar esta imagen?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/inventario/${inventarioId}/eliminar-imagen`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     document.querySelector('input[name="_token"]')?.value;
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    const indiceField = document.createElement('input');
                    indiceField.type = 'hidden';
                    indiceField.name = 'indice';
                    indiceField.value = indiceImagen;
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    form.appendChild(indiceField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // Validación del formulario
            document.querySelector('form').addEventListener('submit', function(e) {
                const files = document.getElementById('nuevas_imagenes').files;
                
                if (files.length > 5) {
                    if (!confirm(`Estás a punto de subir ${files.length} nuevas imágenes. Esto puede tardar un momento. ¿Continuar?`)) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                // Cambiar texto del botón
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn && files.length > 0) {
                    submitBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Actualizando...
                    `;
                    submitBtn.disabled = true;
                }
            });
        </script>

        <!-- Meta tag para CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </body>
</html>