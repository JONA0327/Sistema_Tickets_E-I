@props([
    'theme' => 'blue',
])

@php
    $themes = [
        'blue' => [
            'hover_bg' => 'hover:bg-blue-50',
            'hover_text' => 'hover:text-blue-800',
            'focus_ring' => 'focus-visible:ring-blue-500',
        ],
        'green' => [
            'hover_bg' => 'hover:bg-green-50',
            'hover_text' => 'hover:text-green-800',
            'focus_ring' => 'focus-visible:ring-green-500',
        ],
        'orange' => [
            'hover_bg' => 'hover:bg-orange-50',
            'hover_text' => 'hover:text-orange-800',
            'focus_ring' => 'focus-visible:ring-orange-500',
        ],
        'purple' => [
            'hover_bg' => 'hover:bg-purple-50',
            'hover_text' => 'hover:text-purple-800',
            'focus_ring' => 'focus-visible:ring-purple-500',
        ],
    ];

    $styles = $themes[$theme] ?? $themes['blue'];
    $linkBase = 'flex items-center space-x-1 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-600 '
        . $styles['hover_bg'] . ' ' . $styles['hover_text']
        . ' focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 '
        . $styles['focus_ring'] . ' focus-visible:ring-offset-white';
@endphp

<nav {{ $attributes->merge([
    'class' => 'flex flex-wrap items-center justify-center lg:justify-start gap-2 lg:gap-3 text-sm font-medium text-gray-600',
]) }}>
    <a href="{{ route('welcome') }}" class="{{ $linkBase }}">
        <span>🏠</span>
        <span>Inicio</span>
    </a>

    @if (auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="{{ $linkBase }}">
            <span>⚙️</span>
            <span>Panel Admin</span>
        </a>
    @endif

    <a href="{{ route('tickets.mis-tickets') }}" class="{{ $linkBase }}">
        <span>📋</span>
        <span>Mis Tickets</span>
    </a>

    @if (auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('archivo-problemas.index') }}" class="{{ $linkBase }}">
            <span>📚</span>
            <span>Archivo Problemas</span>
        </a>
    @endif
</nav>
