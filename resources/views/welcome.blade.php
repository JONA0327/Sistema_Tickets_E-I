<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                :root {
                    color-scheme: light;
                    font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                    --bg-gradient-start: #0f172a;
                    --bg-gradient-end: #1d4ed8;
                    --card-bg: #ffffff;
                    --card-border: rgba(15, 23, 42, 0.12);
                    --card-shadow: 0 20px 45px rgba(15, 23, 42, 0.18);
                    --text-primary: #0f172a;
                    --text-secondary: #334155;
                    --accent: #1d4ed8;
                    --accent-hover: #2563eb;
                    --muted: #94a3b8;
                    --error: #dc2626;
                }

                *, *::before, *::after {
                    box-sizing: border-box;
                }

                body {
                    margin: 0;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: radial-gradient(circle at top, rgba(37, 99, 235, 0.35), transparent 60%),
                        linear-gradient(135deg, var(--bg-gradient-start), var(--bg-gradient-end));
                    color: var(--text-primary);
                    font-family: inherit;
                    padding: 2rem;
                }

                .page-wrapper {
                    width: min(1024px, 100%);
                    display: grid;
                    gap: clamp(2rem, 4vw, 4rem);
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                    align-items: center;
                    animation: fade-up 900ms ease forwards;
                    opacity: 0;
                    transform: translateY(32px);
                }

                @keyframes fade-up {
                    from {
                        opacity: 0;
                        transform: translateY(32px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .brand-panel {
                    color: #e2e8f0;
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                }

                .brand-logo {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.75rem;
                    font-weight: 600;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    color: #f8fafc;
                }

                .brand-logo span {
                    width: 42px;
                    height: 42px;
                    border-radius: 12px;
                    background: rgba(255, 255, 255, 0.12);
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.25rem;
                    font-weight: 700;
                    color: #bfdbfe;
                    backdrop-filter: blur(8px);
                }

                .brand-panel h1 {
                    margin: 0;
                    font-size: clamp(2rem, 3vw, 2.75rem);
                    font-weight: 600;
                    line-height: 1.2;
                }

                .brand-panel p {
                    margin: 0;
                    max-width: 32ch;
                    font-size: 1.05rem;
                    color: rgba(226, 232, 240, 0.82);
                    line-height: 1.6;
                }

                .card {
                    background: var(--card-bg);
                    border-radius: 24px;
                    padding: clamp(2rem, 4vw, 3rem);
                    box-shadow: var(--card-shadow);
                    border: 1px solid var(--card-border);
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                    position: relative;
                    overflow: hidden;
                    isolation: isolate;
                }

                .card::before {
                    content: "";
                    position: absolute;
                    inset: -40% auto auto -40%;
                    width: 70%;
                    height: 70%;
                    background: radial-gradient(circle, rgba(37, 99, 235, 0.25), transparent 65%);
                    z-index: -1;
                }

                .card-header {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .card-header h2 {
                    margin: 0;
                    font-size: 1.85rem;
                    font-weight: 600;
                    color: var(--text-primary);
                }

                .card-header p {
                    margin: 0;
                    color: var(--text-secondary);
                    line-height: 1.6;
                }

                form {
                    display: flex;
                    flex-direction: column;
                    gap: 1.25rem;
                }

                label {
                    font-size: 0.95rem;
                    font-weight: 500;
                    color: var(--text-primary);
                    display: block;
                    margin-bottom: 0.5rem;
                }

                input[type="email"],
                input[type="password"] {
                    width: 100%;
                    padding: 0.85rem 1rem;
                    border-radius: 14px;
                    border: 1px solid rgba(15, 23, 42, 0.12);
                    background: rgba(255, 255, 255, 0.9);
                    font-size: 1rem;
                    transition: border-color 180ms ease, box-shadow 180ms ease;
                }

                input[type="email"]:focus,
                input[type="password"]:focus {
                    outline: none;
                    border-color: rgba(37, 99, 235, 0.75);
                    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
                }

                .row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 0.75rem;
                    flex-wrap: wrap;
                }

                .remember {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.95rem;
                    color: var(--text-secondary);
                }

                .remember input {
                    width: 1.1rem;
                    height: 1.1rem;
                    accent-color: var(--accent);
                }

                .forgot a {
                    color: var(--accent);
                    font-weight: 500;
                    text-decoration: none;
                    transition: color 180ms ease;
                }

                .forgot a:hover,
                .register a:hover {
                    color: var(--accent-hover);
                }

                .button-primary {
                    border: none;
                    border-radius: 14px;
                    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
                    color: #ffffff;
                    font-weight: 600;
                    font-size: 1rem;
                    padding: 0.95rem 1rem;
                    cursor: pointer;
                    transition: transform 150ms ease, box-shadow 150ms ease;
                }

                .button-primary:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 18px 35px rgba(37, 99, 235, 0.25);
                }

                .button-primary:focus-visible {
                    outline: 3px solid rgba(37, 99, 235, 0.35);
                    outline-offset: 3px;
                }

                .register {
                    text-align: center;
                    font-size: 0.95rem;
                    color: var(--text-secondary);
                }

                .register a {
                    color: var(--accent);
                    font-weight: 600;
                    text-decoration: none;
                }

                .alert-error {
                    background: rgba(220, 38, 38, 0.08);
                    border-left: 4px solid var(--error);
                    padding: 0.75rem 1rem;
                    border-radius: 12px;
                    color: var(--error);
                    font-size: 0.9rem;
                }

                .dashboard-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    background: rgba(255, 255, 255, 0.12);
                    border-radius: 999px;
                    padding: 0.75rem 1.5rem;
                    color: #f8fafc;
                    text-decoration: none;
                    font-weight: 600;
                    transition: background 150ms ease;
                }

                .dashboard-link:hover {
                    background: rgba(255, 255, 255, 0.2);
                }

                @media (max-width: 720px) {
                    body {
                        padding: 1.5rem;
                    }

                    .card {
                        border-radius: 20px;
                        padding: 2rem;
                    }
                }

                @media (prefers-reduced-motion: reduce) {
                    *, *::before, *::after {
                        animation-duration: 1ms !important;
                        animation-iteration-count: 1 !important;
                        transition-duration: 1ms !important;
                        scroll-behavior: auto !important;
                    }
                }
            </style>
        @endif
    </head>
    <body class="antialiased">
        <div class="page-wrapper">
            <div class="brand-panel">
                <div class="brand-logo">
                    <span>ST</span>
                    {{ config('app.name', 'Sistema de Tickets') }}
                </div>
                <h1>Gestiona tus tickets con confianza.</h1>
                <p>
                    Inicia sesión para acceder a tu panel y dar seguimiento a los casos de soporte con una
                    experiencia rápida, organizada y profesional.
                </p>

                @auth
                    <a href="{{ url('/dashboard') }}" class="dashboard-link">
                        Ir al panel
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"></path>
                            <path d="M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endauth
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Inicia sesión</h2>
                    <p>Bienvenido de nuevo. Ingresa tus credenciales para continuar trabajando.</p>
                </div>

                @auth
                    <div class="alert-error" role="alert">
                        Ya tienes una sesión activa. Utiliza el enlace del panel para continuar.
                    </div>
                @else
                    @if ($errors->any())
                        <div class="alert-error" role="alert">
                            {{ __('Credenciales no válidas. Por favor, verifica tu correo y contraseña.') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <label for="email">Correo electrónico</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nombre@empresa.com">
                        </div>

                        <div>
                            <label for="password">Contraseña</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña">
                        </div>

                        <div class="row">
                            <label class="remember">
                                <input type="checkbox" name="remember">
                                <span>Recordarme</span>
                            </label>

                            @if (Route::has('password.request'))
                                <div class="forgot">
                                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="button-primary">Acceder</button>
                    </form>

                    @if (Route::has('register'))
                        <div class="register">
                            ¿Aún no tienes cuenta?
                            <a href="{{ route('register') }}">Crear una cuenta</a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </body>
</html>
