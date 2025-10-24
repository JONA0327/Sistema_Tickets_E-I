@php
    $user = Auth::user();

    $navItems = [
        [
            'label' => 'Inicio',
            'icon' => '🏠',
            'route' => route('welcome'),
            'active' => request()->routeIs('welcome'),
            'visible' => true,
        ],
        [
            'label' => 'Panel Admin',
            'icon' => '⚙️',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.*'),
            'visible' => $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false,
        ],
        [
            'label' => 'Mis Tickets',
            'icon' => '📋',
            'route' => route('tickets.mis-tickets'),
            'active' => request()->routeIs('tickets.*'),
            'visible' => true,
        ],
        [
            'label' => 'Archivo Problemas',
            'icon' => '📚',
            'route' => route('archivo-problemas.index'),
            'active' => request()->routeIs('archivo-problemas.*'),
            'visible' => $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false,
        ],
    ];

    $filteredItems = array_filter($navItems, fn ($item) => $item['visible']);
    $initials = $user ? strtoupper(mb_substr($user->name, 0, 1, 'UTF-8')) : 'U';
    $roleLabel = ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) ? 'Administrador TI' : 'Usuario';
@endphp

@once
    <style>[x-cloak] { display: none !important; }</style>
@endonce

<nav x-data="{ mobileOpen: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center gap-4">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-10 w-auto">
                    <div class="leading-tight">
                        <p class="text-lg font-semibold text-gray-900">Sistema de Tickets</p>
                        <p class="text-sm text-gray-500">E&amp;I - Tecnología</p>
                    </div>
                </a>
            </div>

            <!-- Desktop navigation - Only visible on large screens -->
            <div class="nav-desktop items-center gap-6">
                <div class="flex items-center gap-2">
                    @foreach ($filteredItems as $item)
                        @php
                            $isActive = $item['active'];
                            $linkClasses = 'inline-flex items-center gap-2 px-3 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 transition-colors duration-200 hover:text-blue-600 hover:border-blue-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white';
                        @endphp
                        <a
                            href="{{ $item['route'] }}"
                            class="{{ $linkClasses }} {{ $isActive ? 'text-blue-600 border-blue-500' : '' }}"
                        >
                            <span>{{ $item['icon'] }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                @if ($user && method_exists($user, 'isAdmin') && $user->isAdmin())
                    <x-admin.notification-center class="hidden xl:flex" />
                @endif

                @if ($user)
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            @click.outside="open = false"
                            class="flex items-center gap-3 px-3 py-2 rounded-full bg-blue-50 text-sm text-gray-700 hover:bg-blue-100 transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        >
                            <span class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-600 text-white font-semibold">
                                {{ $initials }}
                            </span>
                            <span class="text-left">
                                <span class="block font-semibold text-gray-900">{{ $user?->name }}</span>
                                <span class="block text-xs text-gray-500">{{ $user?->email }}</span>
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            x-cloak
                            x-show="open"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-72 bg-white border border-gray-200 rounded-xl shadow-xl z-50"
                        >
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ $user?->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user?->email }}</p>
                                <span class="inline-flex mt-2 items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                    {{ $roleLabel }}
                                </span>
                            </div>

                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 20.5H4v-3.5L16.732 3.732z" />
                                    </svg>
                                    Mi perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4" />
                                        </svg>
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Iniciar sesión
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Solicitar registro
                        </a>
                    </div>
                @endif
            </div>

            <!-- Mobile menu button - Only visible on small screens -->
            <div class="nav-mobile items-center gap-3">
                @if ($user && method_exists($user, 'isAdmin') && $user->isAdmin())
                    <x-admin.notification-center />
                @endif

                <button
                    type="button"
                    @click="mobileOpen = !mobileOpen"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    aria-label="Abrir menú"
                >
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-cloak x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div
        x-cloak
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="lg:hidden border-t border-gray-200 bg-white"
    >
        <div class="px-4 pt-4 pb-6 space-y-4">
            @if ($user)
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-semibold">{{ $initials }}</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user?->email }}</p>
                        <span class="inline-flex mt-1 items-center px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600">{{ $roleLabel }}</span>
                    </div>
                </div>
            @else
                <div class="space-y-2">
                    <a
                        href="{{ route('login') }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Iniciar sesión
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Solicitar registro
                    </a>
                </div>
            @endif

            <div class="space-y-2">
                @foreach ($filteredItems as $item)
                    @php
                        $isActive = $item['active'];
                        $linkClasses = 'flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 transition-colors duration-150';
                    @endphp
                    <a href="{{ $item['route'] }}" class="{{ $linkClasses }} {{ $isActive ? 'bg-blue-50 text-blue-700' : '' }}">
                        <span>{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>

            @if ($user)
                <div class="border-t border-gray-100 pt-4 space-y-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 20.5H4v-3.5L16.732 3.732z" />
                        </svg>
                        Mi perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4" />
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</nav>
