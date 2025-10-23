<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Registro - E&I Sistema de Tickets</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 font-sans antialiased">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900">Sistema de Tickets</h1>
                                    <p class="text-sm text-gray-600">E&I - Tecnología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center sm:justify-end space-x-4">
                        <!-- Placeholder for consistency -->
                    </div>
                </div>
            </div>
        </header>

        <!-- Back to Home Button -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center justify-center sm:justify-start">
                    <a href="{{ route('welcome') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200 group">
                        <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver al Portal de Tickets
                    </a>
                </div>
            </div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Logo/Icon Section -->
            <div class="mb-8">
                <div class="w-20 h-20 bg-blue-600 rounded-xl flex items-center justify-center mx-auto shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-lg border border-blue-100">
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Crear Cuenta</h2>
                    <p class="text-gray-600">Regístrate para crear y gestionar tus tickets</p>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-red-800">Por favor corrige los siguientes errores:</h3>
                        </div>
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name"
                               placeholder="Tu nombre completo"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-300 @enderror">
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username"
                               placeholder="tu@correo.com"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Utiliza tu correo corporativo con el dominio @estrategiaeinnovacion.com.mx.</p>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="Mínimo 8 caracteres"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('password') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Debe tener entre 8 y 16 caracteres e incluir números y símbolos.</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Contraseña
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Repite tu contraseña"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            ¿Ya tienes cuenta? Inicia sesión
                        </a>
                        
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Crear Cuenta
                        </button>
                    </div>
                    <p class="mt-4 text-xs text-gray-500 text-center">Tu solicitud será revisada por un administrador antes de habilitar el acceso.</p>
                </form>
            </div>
        </div>
    </body>
</html>
