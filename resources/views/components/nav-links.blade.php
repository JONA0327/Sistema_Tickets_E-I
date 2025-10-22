@props([
    'theme' => 'blue',
])

@php
    $themes = [
        'blue' => [
            'hover_text' => 'hover:text-blue-600',
            'hover_border' => 'hover:border-blue-400',
            'focus_ring' => 'focus-visible:ring-blue-500',
        ],
        'green' => [
            'hover_text' => 'hover:text-green-600',
            'hover_border' => 'hover:border-green-400',
            'focus_ring' => 'focus-visible:ring-green-500',
        ],
        'orange' => [
            'hover_text' => 'hover:text-orange-600',
            'hover_border' => 'hover:border-orange-400',
            'focus_ring' => 'focus-visible:ring-orange-500',
        ],
        'purple' => [
            'hover_text' => 'hover:text-purple-600',
            'hover_border' => 'hover:border-purple-400',
            'focus_ring' => 'focus-visible:ring-purple-500',
        ],
    ];

    $styles = $themes[$theme] ?? $themes['blue'];
    $linkBase = 'flex items-center gap-1 px-1 py-1 border-b-2 border-transparent text-sm font-medium text-gray-600 transition-colors duration-200'
        . ' ' . $styles['hover_text'] . ' ' . $styles['hover_border']
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
