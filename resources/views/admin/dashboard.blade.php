<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Panel de Administración - Sistema IT</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
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
                                    <h1 class="text-xl font-bold text-gray-900">Panel de Administración</h1>
                                    <p class="text-sm text-gray-600">E&I - Tecnología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications Dropdown -->
                        <div class="relative" x-data="notificationDropdown()" x-init="init()">
                            <button @click="toggleDropdown()" 
                                    class="relative flex items-center justify-center w-11 h-11 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 group">
                                <!-- Modern Bell Icon -->
                                <svg class="w-6 h-6 transform group-hover:rotate-12 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 -5v-5a6 6 0 1 0 -12 0v5l-5 5h5m6 0v1a2 2 0 1 1 -4 0v-1m4 0H9"/>
                                </svg>
                                <!-- Modern notification badge with gradient and glow -->
                                <span x-show="unreadCount > 0" 
                                      x-text="unreadCount > 99 ? '99+' : unreadCount"
                                      class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-gradient-to-r from-red-500 to-pink-500 rounded-full min-w-[22px] h-6 shadow-lg ring-2 ring-white animate-bounce"></span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div x-show="isOpen" 
                                 @click.away="closeDropdown()"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50 max-h-96 overflow-hidden">
                                
                                <!-- Header -->
                                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                    <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                                    <div class="flex items-center space-x-2">
                                        <span x-text="unreadCount" class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-medium"></span>
                                        <button @click="markAllAsRead()" 
                                                x-show="unreadCount > 0"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            Marcar todas como leídas
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Notifications List -->
                                <div class="max-h-80 overflow-y-auto">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-8 text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-sm">No tienes notificaciones nuevas</p>
                                        </div>
                                    </template>
                                    
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                                             @click="openTicket(notification)">
                                            <div class="flex items-start space-x-3">
                                                <!-- Type Icon -->
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                         :class="{
                                                             'bg-blue-100 text-blue-600': notification.tipo === 'Software',
                                                             'bg-orange-100 text-orange-600': notification.tipo === 'Hardware',
                                                             'bg-green-100 text-green-600': notification.tipo === 'Mantenimiento'
                                                         }">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                
                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-gray-900" x-text="'Ticket #' + notification.folio"></p>
                                                        <p class="text-xs text-gray-500" x-text="notification.fecha"></p>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mt-1" x-text="notification.solicitante"></p>
                                                    <p class="text-sm text-gray-800 mt-1" x-text="notification.descripcion"></p>
                                                    <div class="flex items-center mt-2">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                                              :class="{
                                                                  'bg-blue-100 text-blue-800': notification.tipo === 'Software',
                                                                  'bg-orange-100 text-orange-800': notification.tipo === 'Hardware',
                                                                  'bg-green-100 text-green-800': notification.tipo === 'Mantenimiento'
                                                              }"
                                                              x-text="notification.tipo"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Footer -->
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                                    <a href="{{ route('admin.tickets.index') }}" 
                                       class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Ver todos los tickets
                                    </a>
                                </div>
                            </div>
                        </div>

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
                                        Administrador TI
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
                    </div>
                </div>
            </div>
        </header>

        <!-- Back to Home Button -->
        @if (!Auth::user()->isAdmin())
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center">
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

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Panel de <span class="text-blue-600">Administración</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Gestiona tickets, inventario y solicitudes desde el panel administrativo
                </p>
            </div>

            <!-- Admin Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1: Gestión de Tickets -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Gestión de Tickets
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
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
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Inventario
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
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
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-orange-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Solicitudes de Inventario
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
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
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            Gestión de Usuarios
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
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
                    <div class="p-8">
                        <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-lg mb-6 mx-auto">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                            📚 Base de Conocimiento
                        </h3>
                        <p class="text-gray-600 text-center mb-6 leading-relaxed">
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
        <script>
            function notificationDropdown() {
                return {
                    isOpen: false,
                    unreadCount: 0,
                    notifications: [],
                    
                    init() {
                        this.loadNotifications();
                        // Actualizar cada 30 segundos
                        setInterval(() => {
                            this.loadNotifications();
                        }, 30000);
                    },
                    
                    toggleDropdown() {
                        this.isOpen = !this.isOpen;
                        if (this.isOpen) {
                            this.loadNotifications();
                        }
                    },
                    
                    closeDropdown() {
                        this.isOpen = false;
                    },
                    
                    async loadNotifications() {
                        try {
                            const response = await fetch('/api/notifications/unread');
                            const data = await response.json();
                            
                            this.notifications = data.tickets || [];
                            this.unreadCount = data.count || 0;
                        } catch (error) {
                            console.error('Error loading notifications:', error);
                        }
                    },
                    
                    async markAsRead(ticketId) {
                        try {
                            const response = await fetch(`/api/notifications/${ticketId}/read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                }
                            });
                            
                            if (response.ok) {
                                this.loadNotifications();
                            }
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                        }
                    },
                    
                    async markAllAsRead() {
                        try {
                            const response = await fetch('/api/notifications/mark-all-read', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                }
                            });
                            
                            if (response.ok) {
                                this.loadNotifications();
                                this.showToast('Todas las notificaciones marcadas como leídas');
                            }
                        } catch (error) {
                            console.error('Error marking all as read:', error);
                        }
                    },
                    
                    async openTicket(notification) {
                        // Marcar como leída al hacer clic
                        await this.markAsRead(notification.id);
                        
                        // Navegar al ticket
                        window.location.href = notification.url;
                    },
                    
                    showToast(message) {
                        // Crear toast notification
                        const toast = document.createElement('div');
                        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300';
                        toast.textContent = message;
                        
                        document.body.appendChild(toast);
                        
                        // Remover después de 3 segundos
                        setTimeout(() => {
                            toast.classList.add('opacity-0', 'translate-x-full');
                            setTimeout(() => {
                                document.body.removeChild(toast);
                            }, 300);
                        }, 3000);
                    }
                }
            }
        </script>
    </body>
</html>