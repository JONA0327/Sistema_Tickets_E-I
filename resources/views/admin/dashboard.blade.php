@extends('layouts.master')

@section('title', 'Panel de Administración - Sistema IT')

@section('header')
<!-- Back to Home Button -->
@if (!Auth::user()->isAdmin())
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
@endif
@endsection

@section('content')
        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                    Panel de <span class="text-blue-600">Administración</span>
                </h2>
                <p class="text-base sm:text-xl text-gray-600 max-w-2xl mx-auto">
                    Gestiona tickets, inventario y solicitudes desde el panel administrativo
                </p>
            </div>

            <!-- Admin Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1: Gestión de Tickets -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Gestión de Tickets
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed text-sm sm:text-base">
                            Administra todos los tickets de soporte. Ve el estado, asigna prioridades y gestiona resoluciones.
                        </p>
                        <a href="{{ route('admin.tickets.index') }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Gestionar Tickets
                        </a>
                    </div>
                </div>

                <!-- Card 2: Inventario -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Inventario
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed text-sm sm:text-base">
                            Controla el inventario de equipos y hardware. Rastrea ubicaciones, estados y asignaciones.
                        </p>
                        <a href="{{ route('inventario.index') }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Ver Inventario
                        </a>
                    </div>
                </div>

                <!-- Card 3: Solicitudes de Inventario -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-orange-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Solicitudes de Inventario
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed text-sm sm:text-base">
                            Revisa y aprueba solicitudes de nuevo equipamiento y recursos tecnológicos.
                        </p>
                        <a href="{{ route('prestamos.index') }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Ver Solicitudes
                        </a>
                    </div>
                </div>

                <!-- Card 4: Gestión de Usuarios -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Gestión de Usuarios
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed text-sm sm:text-base">
                            Administra usuarios del sistema. Crea administradores y gestiona permisos.
                        </p>
                        <a href="{{ route('admin.users') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Gestionar Usuarios
                        </a>
                    </div>
                </div>

                <!-- Nueva Card: Base de Conocimiento -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100 mt-8">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            📚 Base de Conocimiento
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed text-sm sm:text-base">
                            Gestiona el archivo de problemas resueltos. Categoriza y busca soluciones de tickets cerrados.
                        </p>
                        <div class="space-y-3">
                            <a href="{{ route('archivo-problemas.index') }}" 
                               class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Explorar Archivo
                            </a>
                            <a href="{{ route('archivo-problemas.estadisticas') }}" 
                               class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Estadísticas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-blue-100 mt-16">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} Panel de Administración - Sistema IT. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>

        <!-- Notification System JavaScript -->
@endsection