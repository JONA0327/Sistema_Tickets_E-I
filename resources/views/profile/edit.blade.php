<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Perfil de Usuario - Sistema IT</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100">
        <header class="bg-white/90 backdrop-blur border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 py-5">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-12 w-auto mr-3">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Perfil de Usuario</h1>
                            <p class="text-sm text-gray-600">Administra tu información personal y preferencias de seguridad</p>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-4 md:gap-6" x-data="{ open: false }">
                        @include('components.nav-links', ['theme' => 'blue'])

                        <div class="flex items-center justify-center md:justify-end gap-4">
                            @if (auth()->user()->isAdmin())
                                <x-admin.notification-center />
                            @endif

                            <div class="relative">
                                <button
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors duration-200 border border-transparent hover:border-blue-200"
                                >
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-blue-100 py-2 z-50"
                                    style="display: none;"
                                >
                                    <a href="{{ route('welcome') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                        <span class="mr-2">🏠</span> Inicio
                                    </a>
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-150">
                                            <span class="mr-2">⚙️</span> Panel de Administración
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="bg-white/70 border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                    </svg>
                    <span>Última actualización: {{ optional(auth()->user()->updated_at)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">Perfil activo</span>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('welcome') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ auth()->user()->isAdmin() ? 'Volver al Panel' : 'Volver al Portal de Tickets' }}
                    </a>
                </div>
            </div>
        </div>

        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-2 space-y-8">
                    @include('profile.partials.update-profile-information-form')

                    @include('profile.partials.update-password-form')

                    @include('profile.partials.delete-user-form')
                </div>

                <aside class="space-y-6">
                    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-blue-100 p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Usuario</p>
                                <h2 class="text-lg font-semibold text-gray-900 leading-tight">{{ auth()->user()->name }}</h2>
                                <p class="text-sm text-gray-500 break-all">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <dl class="mt-6 space-y-4 text-sm text-gray-600">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 1.79-7 4v1h14v-1c0-2.21-3.134-4-7-4z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Rol</p>
                                    <p class="font-medium text-gray-900">{{ auth()->user()->isAdmin() ? 'Administrador' : 'Usuario' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a2 2 0 00-2-2H8a2 2 0 00-2 2v2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Miembro desde</p>
                                    <p class="font-medium text-gray-900">{{ optional(auth()->user()->created_at)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A2 2 0 0122 9.528v4.944a2 2 0 01-2.447 1.804L15 14m-6 0l-4.553 2.276A2 2 0 012 14.472V9.528a2 2 0 012.447-1.804L9 10m6 0l-6 4m0-4l6 4" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Preferencias de notificación</p>
                                    <p class="font-medium text-gray-900">Recibe actualizaciones por correo electrónico</p>
                                    <p class="text-xs text-gray-500 mt-1">Gestiona tus notificaciones desde tu perfil de tickets.</p>
                                </div>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-blue-600 text-white rounded-2xl shadow-lg p-6 space-y-4">
                        <h3 class="text-lg font-semibold">Consejos de seguridad</h3>
                        <ul class="space-y-3 text-sm text-blue-50">
                            <li class="flex items-start gap-3">
                                <span class="mt-1">🔒</span>
                                <span>Actualiza tu contraseña regularmente y evita reutilizarla en otros servicios.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1">📧</span>
                                <span>Verifica tu correo electrónico para recibir notificaciones importantes del sistema.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1">✅</span>
                                <span>Revisa tus tickets y solicitudes para asegurarte de que toda la información esté actualizada.</span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </main>
    </body>
</html>
