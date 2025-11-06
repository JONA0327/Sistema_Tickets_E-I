@php
    $user = Auth::user();

    $navItems = [
        [
            'label' => 'Inicio',
            'icon' => 'home',
            'route' => route('welcome'),
            'active' => request()->routeIs('welcome'),
            'visible' => true,
        ],
        [
            'label' => 'Panel Admin',
            'icon' => 'cog-6-tooth',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.*'),
            'visible' => $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false,
        ],
        [
            'label' => 'Mis Tickets',
            'icon' => 'clipboard-document-list',
            'route' => route('tickets.mis-tickets'),
            'active' => request()->routeIs('tickets.*'),
            'visible' => true,
        ],
        [
            'label' => 'Archivo Problemas',
            'icon' => 'archive-box',
            'route' => route('archivo-problemas.index'),
            'active' => request()->routeIs('archivo-problemas.*'),
            'visible' => $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false,
        ],
        [
            'label' => 'Ayuda',
            'icon' => 'lifebuoy',
            'route' => route('help.public'),
            'active' => request()->routeIs('help.*'),
            'visible' => true,
        ],
    ];

    $filteredItems = array_filter($navItems, fn ($item) => $item['visible']);
    $initials = $user ? strtoupper(mb_substr($user->name, 0, 1, 'UTF-8')) : 'U';
    $roleLabel = ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) ? 'Administrador TI' : 'Usuario';
@endphp

@once
    <style>[x-cloak] { display: none !important; }</style>
@endonce

<nav x-data="{ mobileOpen: false }" class="relative z-50 border-b border-blue-900/40 bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-900/20">

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('welcome') }}" class="relative flex items-center gap-3 px-1.5 py-1.5">
                    <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-9 w-auto flex-shrink-0">
                    <div class="min-w-0 leading-tight">
                        <p class="text-sm sm:text-base font-semibold text-white truncate" style="max-width:220px">Sistema de Tickets</p>
                        <p class="text-xs sm:text-sm font-medium text-white/70 truncate" style="max-width:220px">E&amp;I - Tecnología</p>
                    </div>
                </a>
            </div>

            @auth
                <div class="hidden items-center gap-6 lg:flex pr-4 lg:pr-8">
                    <div class="flex items-center gap-2 pr-4">
                        @foreach ($filteredItems as $item)
                            @php
                                $isActive = $item['active'];
                                $linkClasses = 'group inline-flex items-center gap-3 rounded-2xl px-4 py-2 text-sm font-semibold transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-blue-700';
                                $activeClasses = 'bg-white text-blue-700 shadow-lg shadow-blue-900/10 ring-1 ring-blue-100/60';
                                $inactiveClasses = 'text-white/80 hover:text-white hover:bg-white/10';
                                $iconWrapper = $isActive
                                    ? 'bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-md shadow-blue-500/30'
                                    : 'bg-white/10 text-white transition-colors duration-200 group-hover:bg-white/20 group-hover:text-white';
                            @endphp
                            <a href="{{ $item['route'] }}" class="{{ $linkClasses }} {{ $isActive ? $activeClasses : $inactiveClasses }}">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $iconWrapper }}">
                                    <x-ui.icon :name="$item['icon']" class="h-5 w-5" />
                                </span>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>

                    @if ($user && method_exists($user, 'isAdmin') && $user->isAdmin())
                        <x-admin.notification-center class="hidden xl:flex" />
                    @endif

                    @if ($user)
                        <div class="relative flex-shrink-0" x-data="{ open: false }">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="open = !open"
                                    @click.outside="open = false"
                                    class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-2 py-2 text-sm text-white shadow-sm shadow-blue-900/20 transition-all duration-200 hover:bg-white/20 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-white/80 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-700"
                                >
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-blue-500 text-white font-semibold shadow-md shadow-blue-500/30">
                                        {{ $initials }}
                                    </span>
                                    <svg class="h-4 w-4 text-white/70 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>

                            <div
                                x-cloak
                                x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-72 overflow-hidden rounded-2xl border border-blue-100/80 bg-white/95 shadow-2xl shadow-blue-500/10 backdrop-blur-xl z-60"
                            >
                                <div class="border-b border-blue-100/70 bg-gradient-to-r from-blue-50/70 to-white px-4 py-3">
                                    <p class="text-sm font-semibold text-slate-900">{{ $user?->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user?->email }}</p>
                                    <span class="mt-2 inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                        <x-ui.icon name="check-badge" class="h-4 w-4" />
                                        {{ $roleLabel }}
                                    </span>
                                </div>

                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-slate-600 transition-colors duration-150 hover:bg-blue-50/60">
                                        <x-ui.icon name="pencil-square" class="mr-3 h-5 w-5 text-slate-400" />
                                        Mi perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-4 py-2 text-sm font-medium text-red-600 transition-colors duration-150 hover:bg-red-50">
                                            <x-ui.icon name="arrow-right-on-rectangle" class="mr-3 h-5 w-5 text-red-400" />
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endauth

            @auth
                <div class="flex items-center gap-3 lg:hidden text-white">
                    @if ($user && method_exists($user, 'isAdmin') && $user->isAdmin())
                        <x-admin.notification-center />
                    @endif

                    <button
                        type="button"
                        @click="mobileOpen = !mobileOpen"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-white shadow-sm shadow-blue-900/20 transition-colors duration-200 hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/80 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-700"
                        aria-label="Abrir menú"
                    >
                        <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-cloak x-show="mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endauth
        </div>
    </div>

    @auth
        <div
            x-cloak
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="lg:hidden border-t border-blue-100 bg-white/95 backdrop-blur"
        >
            <div class="space-y-4 px-4 pt-4 pb-6">
                @if ($user)
                    <div class="flex items-center gap-3 rounded-2xl border border-blue-100/70 bg-blue-50/50 px-3 py-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-blue-500 text-white font-semibold shadow-md shadow-blue-500/30">{{ $initials }}</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $user?->name }}</p>
                            <p class="text-xs text-slate-500">{{ $user?->email }}</p>
                            <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-white/80 px-2 py-0.5 text-xs font-medium text-blue-600">
                                <x-ui.icon name="check-badge" class="h-4 w-4" />
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="space-y-2">
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-blue-100/70 bg-white/90 px-3 py-2 text-sm font-semibold text-blue-700 shadow-sm shadow-blue-500/10 transition-colors duration-200 hover:bg-blue-50"
                        >
                            <x-ui.icon name="arrow-right-on-rectangle" class="h-4 w-4" />
                            Iniciar sesión
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition-colors duration-200 hover:from-blue-700 hover:to-blue-800"
                        >
                            <x-ui.icon name="sparkles" class="h-4 w-4" />
                            Solicitar registro
                        </a>
                    </div>
                @endif

                <div class="space-y-2">
                    @foreach ($filteredItems as $item)
                        @php
                            $isActive = $item['active'];
                            $linkClasses = 'flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-medium transition-all duration-150';
                            $activeClasses = 'bg-blue-50 text-blue-700 shadow-inner shadow-blue-500/10';
                            $inactiveClasses = 'text-slate-600 hover:bg-blue-50/70 hover:text-blue-700';
                            $iconWrapper = $isActive
                                ? 'bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-md shadow-blue-500/30'
                                : 'bg-blue-50 text-blue-600 group-hover:bg-blue-100 group-hover:text-blue-700';
                        @endphp
                        <a href="{{ $item['route'] }}" class="group {{ $linkClasses }} {{ $isActive ? $activeClasses : $inactiveClasses }}">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $iconWrapper }}">
                                <x-ui.icon :name="$item['icon']" class="h-5 w-5" />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                @if ($user)
                    <div class="space-y-2 border-t border-blue-100/60 pt-4">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-2xl px-3 py-2 text-sm text-slate-600 transition-colors duration-150 hover:bg-blue-50/70">
                            <x-ui.icon name="pencil-square" class="h-5 w-5 text-slate-400" />
                            Mi perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-3 py-2 text-sm font-medium text-red-600 transition-colors duration-150 hover:bg-red-50">
                                <x-ui.icon name="arrow-right-on-rectangle" class="h-5 w-5 text-red-400" />
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endauth
</nav>
