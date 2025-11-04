@php
    $user = Auth::user();

    $navItems = [
        [
            'label' => 'Inicio',
            'route' => route('welcome'),
            'active' => request()->routeIs('welcome'),
            'visible' => true,
        ],
        [
            'label' => 'Tickets',
            'route' => auth()->check() ? route('tickets.mis-tickets') : route('login'),
            'active' => request()->routeIs('tickets.*'),
            'visible' => auth()->check(),
        ],
        [
            'label' => 'Archivo',
            'route' => route('archivo-problemas.index'),
            'active' => request()->routeIs('archivo-problemas.*'),
            'visible' => $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false,
        ],
        [
            'label' => 'Admin',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.*'),
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

<nav x-data="{ mobileOpen: false }" class="bg-white border-b border-[var(--border)] shadow-sm">
    <div class="mx-auto max-w-[1280px] px-6">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]">
                <img src="{{ asset('images/logo-ei.png') }}" alt="E&I Logo" class="h-10 w-auto" loading="lazy">
                <div class="leading-none">
                    <p class="text-base font-semibold text-[var(--text)]">Sistema de Tickets</p>
                    <p class="text-xs text-[var(--muted)]">E&amp;I Tecnología</p>
                </div>
            </a>

            <!-- Desktop navigation -->
            <div class="hidden lg:flex items-center gap-6">
                @auth
                    <div class="flex items-center gap-2">
                        @foreach ($filteredItems as $item)
                            @php
                                $isActive = $item['active'];
                                $linkClasses = 'group relative inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-[var(--muted)] transition-colors duration-200 hover:text-[var(--text)] hover:underline decoration-2 decoration-[var(--primary)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]';
                            @endphp
                            <a
                                href="{{ $item['route'] }}"
                                class="{{ $linkClasses }} {{ $isActive ? 'text-[var(--text)] underline decoration-[var(--primary)]' : '' }}"
                                aria-current="{{ $isActive ? 'page' : 'false' }}"
                            >
                                {{ $item['label'] }}
                                <span class="absolute inset-x-0 -bottom-1 h-0.5 bg-[var(--primary)] transition {{ $isActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}" aria-hidden="true"></span>
                            </a>
                        @endforeach
                    </div>

                    @if ($user && method_exists($user, 'isAdmin') && $user->isAdmin())
                        <x-admin.notification-center class="hidden xl:flex" />
                    @endif

                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            @click.outside="open = false"
                            class="flex items-center gap-3 px-3 py-2 rounded-full bg-[rgba(47,107,255,0.08)] text-sm text-[var(--text)] transition-colors duration-200 hover:bg-[rgba(47,107,255,0.12)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]"
                        >
                            <span class="flex items-center justify-center w-9 h-9 rounded-full bg-[var(--primary)] text-white font-semibold">
                                {{ $initials }}
                            </span>
                            <span class="text-left leading-tight">
                                <span class="block font-semibold text-[var(--text)]">{{ $user?->name }}</span>
                                <span class="block text-xs text-[var(--muted)]">{{ $user?->email }}</span>
                            </span>
                            <svg class="w-5 h-5 text-[var(--muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 9l6 6 6-6" />
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
                            class="absolute right-0 mt-3 w-72 bg-white border border-[var(--border)] rounded-2xl shadow-xl z-50"
                        >
                            <div class="px-4 py-4 border-b border-[var(--border)]">
                                <p class="text-sm font-semibold text-[var(--text)]">{{ $user?->name }}</p>
                                <p class="text-xs text-[var(--muted)]">{{ $user?->email }}</p>
                                <span class="inline-flex mt-2 items-center px-2 py-1 text-xs font-medium rounded-full bg-[#EEF2FF] text-[var(--primary)]">
                                    {{ $roleLabel }}
                                </span>
                            </div>

                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-[var(--muted)] hover:bg-[rgba(47,107,255,0.08)] hover:text-[var(--text)] transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-3 text-[var(--muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 20.5H4v-3.5L16.732 3.732z" />
                                    </svg>
                                    Mi perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-[var(--danger)] hover:bg-[rgba(229,72,77,0.08)] transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-3 text-[var(--danger)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4" />
                                        </svg>
                                        Salir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center h-11 px-4 text-sm font-semibold text-[var(--text)] border border-[var(--border)] rounded-[var(--radius-button)] transition-colors duration-200 hover:border-[var(--primary)] hover:text-[var(--primary)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]"
                        >
                            Iniciar sesión
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center justify-center h-11 px-4 text-sm font-semibold text-white rounded-[var(--radius-button)] bg-[var(--primary)] transition-colors duration-200 hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]"
                        >
                            Solicitar registro
                        </a>
                    </div>
                @endguest
            </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center gap-3 lg:hidden">
                @auth
                    @if ($user && method_exists($user, 'isAdmin') && $user->isAdmin())
                        <x-admin.notification-center />
                    @endif
                @endauth

                <button
                    type="button"
                    @click="mobileOpen = !mobileOpen"
                    class="inline-flex items-center justify-center w-11 h-11 rounded-[var(--radius-button)] bg-[rgba(47,107,255,0.08)] text-[var(--primary)] hover:bg-[rgba(47,107,255,0.12)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]"
                    aria-label="Abrir menú"
                >
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                    <svg x-cloak x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
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
        class="lg:hidden border-t border-[var(--border)] bg-white"
    >
        <div class="px-4 pt-4 pb-6 space-y-4">
            @auth
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-11 h-11 rounded-full bg-[var(--primary)] text-white font-semibold">{{ $initials }}</span>
                    <div>
                        <p class="text-sm font-semibold text-[var(--text)]">{{ $user?->name }}</p>
                        <p class="text-xs text-[var(--muted)]">{{ $user?->email }}</p>
                        <span class="inline-flex mt-1 items-center px-2 py-0.5 text-xs font-medium rounded-full bg-[#EEF2FF] text-[var(--primary)]">{{ $roleLabel }}</span>
                    </div>
                </div>
            @else
                <div class="space-y-3">
                    <a
                        href="{{ route('login') }}"
                        class="w-full inline-flex items-center justify-center h-11 px-4 rounded-[var(--radius-button)] text-sm font-semibold text-[var(--text)] border border-[var(--border)] hover:border-[var(--primary)] hover:text-[var(--primary)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]"
                    >
                        Iniciar sesión
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="w-full inline-flex items-center justify-center h-11 px-4 rounded-[var(--radius-button)] text-sm font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]"
                    >
                        Solicitar registro
                    </a>
                </div>
            @endauth

            @auth
                <div class="space-y-2">
                    @foreach ($filteredItems as $item)
                        @php
                            $isActive = $item['active'];
                            $linkClasses = 'flex items-center justify-between px-3 py-2 rounded-[var(--radius-button)] text-sm font-medium text-[var(--muted)] hover:bg-[rgba(47,107,255,0.08)] hover:text-[var(--text)] transition-colors duration-150 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]';
                        @endphp
                        <a href="{{ $item['route'] }}" class="{{ $linkClasses }} {{ $isActive ? 'bg-[rgba(47,107,255,0.12)] text-[var(--text)]' : '' }}" aria-current="{{ $isActive ? 'page' : 'false' }}">
                            <span>{{ $item['label'] }}</span>
                            @if ($isActive)
                                <span class="inline-flex w-2 h-2 rounded-full bg-[var(--primary)]" aria-hidden="true"></span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="border-t border-[var(--border)] pt-4 space-y-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-button)] text-sm text-[var(--muted)] hover:bg-[rgba(47,107,255,0.08)] hover:text-[var(--text)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]">
                        <svg class="w-5 h-5 text-[var(--muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 20.5H4v-3.5L16.732 3.732z" />
                        </svg>
                        Mi perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-[var(--radius-button)] text-sm text-[var(--danger)] hover:bg-[rgba(229,72,77,0.08)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]">
                            <svg class="w-5 h-5 text-[var(--danger)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4" />
                            </svg>
                            Salir
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>
