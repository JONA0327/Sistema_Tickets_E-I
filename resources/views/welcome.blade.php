@extends('layouts.master')

@section('title', 'Sistema de Tickets - E&I Tecnología')

@section('content')
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

            @guest
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
                        Para crear y gestionar tus tickets de soporte técnico, inicia sesión con tu correo corporativo.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-3">
                        <a href="{{ route('login') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-white border border-blue-200 hover:border-blue-400 hover:bg-blue-50 text-blue-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Solicitar Registro
                        </a>
                    </div>
                    <p class="mt-4 text-xs sm:text-sm text-gray-500">Las nuevas cuentas deben ser aprobadas por el administrador para garantizar que pertenezcan a la organización.</p>
                </div>
            </div>
            @endguest

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
@endsection