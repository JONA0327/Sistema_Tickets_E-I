<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistema de Tickets - E&I Tecnología')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')

    <style>
        [x-cloak] { display: none !important; }
        
        /* Navegación responsive - Forzar comportamiento correcto */
        .nav-desktop {
            display: none !important;
        }
        
        .nav-mobile {
            display: flex !important;
        }
        
        /* En pantallas grandes (desktop) */
        @media (min-width: 1024px) {
            .nav-desktop {
                display: flex !important;
            }
            .nav-mobile {
                display: none !important;
            }
            .lg\:hidden {
                display: none !important;
            }
            .lg\:flex {
                display: flex !important;
            }
        }
        
        /* En pantallas pequeñas (móvil/tablet) */
        @media (max-width: 1023px) {
            .nav-desktop {
                display: none !important;
            }
            .nav-mobile {
                display: flex !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    @include('layouts.navigation')

    @yield('header')

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>