<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrar Préstamo - E&I Sistema de Préstamos</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900">Registrar Préstamo</h1>
                                    <p class="text-sm text-gray-600">E&I - Gestión de Préstamos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center md:flex-row md:items-center md:justify-end gap-4 md:gap-6" x-data="{ open: false }">
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
                                <a href="{{ route('inventario.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🏠 Inicio</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation Breadcrumbs -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-4 h-12 text-sm">
                    <a href="{{ route('inventario.index') }}" class="text-blue-600 hover:text-blue-800">🏠 Inicio</a>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('prestamos.index') }}" class="text-blue-600 hover:text-blue-800">Préstamos</a>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">Registrar Préstamo</span>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Form Container -->
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h2 class="text-xl font-bold text-white">Registrar Nuevo Préstamo</h2>
                            <p class="text-blue-100 text-sm">Complete los datos del préstamo</p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Por favor corrige los siguientes errores:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('prestamos.store') }}" class="p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Información del Artículo -->
                        @if($inventario ?? null)
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h3 class="text-md font-medium text-blue-800 mb-2">📦 Artículo Seleccionado</h3>
                                <div class="text-sm text-blue-700">
                                    <p><strong>Código:</strong> {{ $inventario->codigo_inventario }}</p>
                                    <p><strong>Artículo:</strong> {{ $inventario->articulo }}</p>
                                    <p><strong>Modelo:</strong> {{ $inventario->modelo }}</p>
                                    <p><strong>Categoría:</strong> {{ $inventario->categoria_formateada }}</p>
                                </div>
                                <input type="hidden" name="inventario_id" value="{{ $inventario->id }}">
                            </div>
                        @else
                            <!-- Selector de inventario -->
                            <div>
                                <label for="inventario_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Artículo a prestar *
                                </label>
                                <select name="inventario_id" 
                                        id="inventario_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                        required>
                                    <option value="">Seleccionar artículo</option>
                                    @foreach(\App\Models\Inventario::disponibles()->where('bloqueado_prestamo', false)->orderBy('articulo')->get() as $item)
                                        <option value="{{ $item->id }}" 
                                                data-disponible="{{ $item->cantidad_disponible }}"
                                                {{ (old('inventario_id') == $item->id) ? 'selected' : '' }}>
                                            {{ $item->codigo_inventario }} - {{ $item->articulo }} {{ $item->modelo }} 
                                            ({{ $item->cantidad_disponible }} disponibles)
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventario_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Usuario -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Usuario solicitante *
                            </label>
                            <select name="user_id" 
                                    id="user_id" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    required>
                                <option value="">Seleccionar usuario</option>
                                @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id') == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                        @if($user->role === 'admin') - Administrador @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cantidad -->
                        <div>
                            <label for="cantidad_prestada" class="block text-sm font-medium text-gray-700 mb-1">
                                Cantidad a prestar *
                            </label>
                            <input type="number" 
                                   name="cantidad_prestada" 
                                   id="cantidad_prestada" 
                                   value="{{ old('cantidad_prestada', 1) }}"
                                   min="1"
                                   @if($inventario ?? null)
                                       max="{{ $inventario->cantidad_disponible }}"
                                   @endif
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   required
                                   placeholder="1">
                            @error('cantidad_prestada')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($inventario ?? null)
                                <p class="mt-1 text-xs text-gray-500">
                                    💡 Disponibles: {{ $inventario->cantidad_disponible }} unidades
                                </p>
                            @endif
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label for="observaciones_prestamo" class="block text-sm font-medium text-gray-700 mb-1">
                                Observaciones del préstamo
                            </label>
                            <textarea name="observaciones_prestamo" 
                                      id="observaciones_prestamo" 
                                      rows="3"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Motivo del préstamo, uso previsto, condiciones especiales, etc.">{{ old('observaciones_prestamo') }}</textarea>
                            @error('observaciones_prestamo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('prestamos.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            💾 Guardar Préstamo
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-8 w-auto">
                        <div class="text-sm text-gray-600">
                            <p>&copy; {{ date('Y') }} E&I - Sistema de Gestión de Préstamos</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        Versión 2.0 | Desarrollado para E&I
                    </div>
                </div>
            </div>
        </footer>

        <script>
            // Actualizar cantidad máxima basada en inventario seleccionado
            document.getElementById('inventario_id')?.addEventListener('change', function() {
                const cantidadInput = document.getElementById('cantidad_prestada');
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption && selectedOption.dataset.disponible) {
                    const disponible = selectedOption.dataset.disponible;
                    cantidadInput.max = disponible;
                    cantidadInput.placeholder = `Máximo: ${disponible}`;
                    
                    // Actualizar texto de ayuda
                    const helpText = cantidadInput.parentNode.querySelector('.text-xs');
                    if (helpText) {
                        helpText.textContent = `💡 Disponibles: ${disponible} unidades`;
                    }
                } else {
                    cantidadInput.max = '';
                    cantidadInput.placeholder = '1';
                }
            });
        </script>
    </body>
</html>
