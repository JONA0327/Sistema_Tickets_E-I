<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sistema de Tickets - E&I Tecnología</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
        </style>
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
        <!-- Header -->
        <header x-data="{ mobileOpen: false }" class="bg-white shadow-sm border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4">
                    <div class="flex items-center min-w-0">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                                <div class="min-w-0">
                                    <h1 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">Sistema de Tickets</h1>
                                    <p class="text-xs sm:text-sm text-gray-600">E&I - Tecnología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        @if (Auth::check())
                            @if (Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium mr-4">
                                    Panel Admin
                                </a>
                            @else
                                <a href="{{ route('tickets.mis-tickets') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium mr-4">
                                    Mis Tickets
                                </a>
                            @endif
                            
                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="flex items-center space-x-2 text-sm rounded-full bg-blue-50 p-2 text-gray-700 hover:bg-blue-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                    <div class="py-1">
                                        <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                                            @if (Auth::user()->isAdmin())
                                                Administrador TI
                                            @else
                                                Usuario
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-800 transition-colors duration-200 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Cerrar Sesión
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex space-x-3">
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 px-3 py-2 text-sm font-medium transition-colors duration-200">
                                    Iniciar Sesión
                                </a>
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    Registrarse
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="md:hidden flex items-center">
                        <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="sr-only">Abrir menú principal</span>
                            <svg class="h-6 w-6" x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg class="h-6 w-6" x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div x-cloak x-show="mobileOpen" x-transition class="md:hidden border-t border-blue-100 bg-white">
                <div class="px-4 py-4 space-y-4">
                    @if (Auth::check())
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block text-sm font-medium text-blue-600 hover:text-blue-800">
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('tickets.mis-tickets') }}" class="block text-sm font-medium text-blue-600 hover:text-blue-800">
                                Mis Tickets
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-blue-50">
                            @csrf
                            <button type="submit" class="w-full text-left text-sm font-medium text-red-600 hover:text-red-700 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    @else
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('login') }}" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="w-full text-center border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                Registrarse
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8 mx-auto max-w-4xl">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-green-800 font-medium">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Info Message -->
            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8 mx-auto max-w-4xl">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-blue-800 font-medium">
                                {{ session('info') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                    Centro de <span class="text-blue-600">Soporte Técnico</span>
                </h2>
                <p class="text-base sm:text-xl text-gray-600 max-w-2xl mx-auto">
                    Gestiona tus solicitudes de soporte técnico de manera rápida y eficiente
                </p>
            </div>

            @auth
            <!-- Quick Access Section -->
            <div class="text-center mb-12">
                <div class="bg-white rounded-xl shadow-lg border border-blue-100 p-6 sm:p-8 max-w-md mx-auto">
                    <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-lg mb-4 mx-auto">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">¿Ya tienes un ticket?</h3>
                    <p class="text-gray-600 mb-4 text-sm sm:text-base">Consulta el estado de tus tickets existentes</p>
                    <a href="{{ route('tickets.mis-tickets') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Ver Mis Tickets
                    </a>
                </div>
            </div>
            @else
            <!-- Login/Register Section for Non-Authenticated Users -->
            <div class="text-center mb-12">
                <div class="bg-white rounded-xl shadow-lg border border-blue-100 p-6 sm:p-8 max-w-2xl mx-auto">
                    <div class="flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-blue-100 rounded-lg mb-6 mx-auto">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">¡Bienvenido al Sistema de Tickets!</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed text-sm sm:text-base">
                        Para crear y gestionar tus tickets de soporte técnico, necesitas iniciar sesión o crear una cuenta.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-3 sm:space-x-4">
                        <a href="{{ route('login') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}"
                           class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Crear Cuenta
                        </a>
                    </div>
                </div>
            </div>
            @endauth

            @auth
            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1: Reportar Problema de Software -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Reportar Problema de Software
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
                            ¿Tienes problemas con algún programa o aplicación? Reporta errores, fallos o comportamientos inesperados.
                        </p>
                        <a href="{{ route('tickets.create', 'software') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Reporte
                        </a>
                    </div>
                </div>

                <!-- Card 2: Programar Mantenimiento -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12h.01M8 16h8"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Programar Mantenimiento
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
                            Solicita mantenimiento preventivo o correctivo para tus equipos. Programa revisiones y actualizaciones.
                        </p>
                        <a href="{{ route('tickets.create', 'mantenimiento') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Programar Cita
                        </a>
                    </div>
                </div>

                <!-- Card 3: Problema de Equipo -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Reportar Problema de Equipo
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
                            ¿Tu computadora, impresora u otro equipo no funciona correctamente? Reporta problemas de hardware.
                        </p>
                        <a href="{{ route('tickets.create', 'hardware') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.084 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Reportar Falla
                        </a>
                    </div>
                </div>
            </div>
            @endauth
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-blue-100 mt-16">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} E&I - Comercio Exterior, Logística y Tecnología. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </body>
</html>