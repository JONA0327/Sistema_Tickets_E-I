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
                <div class="flex justify-between items-center h-16">
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
                    <div class="flex items-center space-x-4" x-data="{ open: false }">
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
        <main class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
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
                        $unidades = [];
                        
                        if ($esMultiple) {
                            $partes = explode('--- DETALLES POR UNIDAD ---', $inventario->observaciones);
                            $detalles = trim($partes[1] ?? '');
                            
                            if ($detalles) {
                                $lineas = array_filter(explode("\n", $detalles));
                                foreach ($lineas as $linea) {
                                    if (str_contains($linea, 'UNIDAD')) {
                                        $partes_unidad = explode(' | ', $linea);
                                        $unidad = [
                                            'numero' => trim(str_replace('UNIDAD', '', $partes_unidad[0] ?? '')),
                                            'estado' => 'nuevo',
                                            'color' => '',
                                            'observaciones' => ''
                                        ];
                                        
                                        foreach ($partes_unidad as $parte) {
                                            if (str_contains($parte, 'Estado:')) {
                                                $unidad['estado'] = strtolower(trim(str_replace('Estado:', '', $parte)));
                                            } elseif (str_contains($parte, 'Color:')) {
                                                $unidad['color'] = trim(str_replace('Color:', '', $parte));
                                            } elseif (str_contains($parte, 'Notas:')) {
                                                $unidad['observaciones'] = trim(str_replace('Notas:', '', $parte));
                                            }
                                        }
                                        $unidades[] = $unidad;
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    <form action="{{ route('inventario.update', $inventario) }}" method="POST" enctype="multipart/form-data" 
                          x-data="{ 
                              categoria: '{{ $inventario->categoria }}',
                              esMultiple: {{ $esMultiple ? 'true' : 'false' }},
                              unidades: @js($unidades)
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

                                <!-- Campos para unidad única -->
                                <div x-show="!esMultiple" class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">Cantidad *</label>
                                        <input type="number" 
                                               name="cantidad" 
                                               id="cantidad" 
                                               value="{{ old('cantidad', $inventario->cantidad) }}"
                                               min="1"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                               :required="!esMultiple">
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
                                                :required="!esMultiple">
                                            <option value="">Seleccionar estado</option>
                                            @foreach($estados as $key => $label)
                                                <option value="{{ $key }}" {{ (old('estado', $inventario->estado) == $key) ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

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

                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
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

                                                        <!-- Color (opcional) -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Color (opcional)</label>
                                                            <input type="text" 
                                                                   x-model="unidad.color"
                                                                   :name="'unidades[' + index + '][color]'"
                                                                   class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                                   placeholder="Ej: Negro, Blanco, Azul">
                                                        </div>

                                                        <!-- Observaciones específicas -->
                                                        <div>
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
                                            trim(str_replace('GENERAL:', '', explode('--- DETALLES POR UNIDAD ---', $inventario->observaciones)[0] ?? '')) : 
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
                                                    <img src="data:image/jpeg;base64,{{ $imagen }}" class="w-full h-20 object-cover" />
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
                                           accept="image/*"
                                           class="w-full border border-gray-300 rounded-md p-2 focus:border-green-500 focus:ring-green-500">
                                    <div class="mt-1 flex items-center space-x-4">
                                        <p class="text-xs text-gray-500">
                                            <strong>💡 Tip:</strong> Mantén <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Ctrl</kbd> presionado para seleccionar múltiples archivos
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