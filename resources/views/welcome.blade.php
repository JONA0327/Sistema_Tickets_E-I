@extends('layouts.master')

@section('title', 'Sistema de Tickets - E&I Tecnología')

@section('content')
    <main class="py-10 sm:py-12">
        <div class="mx-auto max-w-[1280px] px-6">
            @if(session('success'))
                <div class="mb-6 flex items-start gap-3 rounded-[var(--radius-card)] border border-[rgba(27,169,127,0.35)] bg-[rgba(27,169,127,0.12)] px-5 py-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[rgba(27,169,127,0.18)]" aria-hidden="true">
                        <svg class="h-5 w-5 text-[var(--success)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <p class="text-sm font-medium text-[var(--text)] leading-[1.5]">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 flex items-start gap-3 rounded-[var(--radius-card)] border border-[rgba(47,107,255,0.35)] bg-[rgba(47,107,255,0.12)] px-5 py-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[rgba(47,107,255,0.18)]" aria-hidden="true">
                        <svg class="h-5 w-5 text-[var(--primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <p class="text-sm font-medium text-[var(--text)] leading-[1.5]">{{ session('info') }}</p>
                </div>
            @endif

            <header class="text-center">
                <h1 class="text-[34px] font-semibold leading-[1.2] text-[var(--text)]">Centro de Soporte Técnico</h1>
                <p class="mx-auto mt-3 max-w-2xl text-[18px] font-normal leading-[1.5] text-[var(--muted)]">
                    Gestiona tus solicitudes de soporte técnico de manera rápida y eficiente.
                </p>
            </header>

            @guest
                <section class="mt-12">
                    <div class="mx-auto max-w-xl rounded-[var(--radius-card)] border border-[var(--border)] bg-white p-8 shadow-[0_2px_10px_rgba(16,24,40,0.06)]">
                        <div class="mx-auto mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-[rgba(47,107,255,0.12)]">
                            <svg class="h-7 w-7 text-[var(--primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a3 3 0 11-6 0 3 3 0 016 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                            </svg>
                        </div>
                        <h2 class="text-[24px] font-semibold leading-[1.2] text-[var(--text)] text-center">Ingresa para gestionar tus tickets</h2>
                        <p class="mt-4 text-sm leading-[1.5] text-[var(--muted)] text-center">
                            Accede con tu correo corporativo para crear, dar seguimiento y resolver incidencias de TI.
                        </p>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                            <a href="{{ route('login') }}" class="inline-flex h-12 items-center justify-center rounded-[var(--radius-button)] border border-[var(--border)] px-6 text-sm font-semibold text-[var(--text)] transition-colors duration-200 hover:border-[var(--primary)] hover:text-[var(--primary)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex h-12 items-center justify-center rounded-[var(--radius-button)] bg-[var(--primary)] px-6 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]">
                                Solicitar registro
                            </a>
                        </div>
                        <p class="mt-4 text-xs leading-[1.5] text-[var(--muted)] text-center">
                            Las nuevas cuentas se validan para garantizar que pertenezcan a la organización.
                        </p>
                    </div>
                </section>
            @endguest

            @auth
                <section class="mt-12" aria-labelledby="acciones-rapidas">
                    <div class="flex items-center justify-between gap-4">
                        <h2 id="acciones-rapidas" class="text-[24px] font-semibold leading-[1.2] text-[var(--text)]">Acciones rápidas</h2>
                        <span class="hidden text-sm font-medium text-[var(--muted)] md:inline">Selecciona el flujo que necesitas</span>
                    </div>
                    <div class="mt-8 grid grid-cols-1 gap-[var(--gap)] md:grid-cols-3">
                        <article class="mx-auto flex w-full max-w-[360px] min-h-[320px] flex-col justify-between rounded-[var(--radius-card)] border border-[var(--border)] bg-white p-7 shadow-[0_2px_10px_rgba(16,24,40,0.06)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_10px_30px_rgba(15,23,42,0.12)] focus-within:outline focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-[var(--focus)]" aria-labelledby="card-software-title">
                            <div>
                                <span class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[rgba(47,107,255,0.1)]">
                                    <svg class="h-10 w-10 text-[var(--primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M4 9h16M9 9v10m6-10v10M4 19h16" />
                                    </svg>
                                </span>
                                <h3 id="card-software-title" class="text-[20px] font-semibold leading-[1.2] text-[var(--text)]">Reportar Software</h3>
                                <p class="mt-3 text-[15px] leading-[1.5] text-[var(--muted)]" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
                                    Reporta fallas de aplicaciones o comportamientos inesperados.
                                </p>
                            </div>
                            <a href="{{ route('tickets.create', 'software') }}" class="mt-6 inline-flex h-12 w-full items-center justify-center gap-2 rounded-[var(--radius-button)] bg-[var(--primary)] px-4 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]" aria-label="Reportar Software">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M4 9h16M9 9v10m6-10v10M4 19h16" />
                                </svg>
                                Crear Reporte
                            </a>
                        </article>

                        <article class="mx-auto flex w-full max-w-[360px] min-h-[320px] flex-col justify-between rounded-[var(--radius-card)] border border-[var(--border)] bg-white p-7 shadow-[0_2px_10px_rgba(16,24,40,0.06)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_10px_30px_rgba(15,23,42,0.12)] focus-within:outline focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-[var(--focus)]" aria-labelledby="card-mantenimiento-title">
                            <div>
                                <span class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[rgba(47,107,255,0.1)]">
                                    <svg class="h-10 w-10 text-[var(--primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-3-3v3m-7 4h14M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <h3 id="card-mantenimiento-title" class="text-[20px] font-semibold leading-[1.2] text-[var(--text)]">Agendar Mantenimiento</h3>
                                <p class="mt-3 text-[15px] leading-[1.5] text-[var(--muted)]" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
                                    Solicita mantenimiento preventivo o correctivo. Revisiones y actualizaciones.
                                </p>
                            </div>
                            <a href="{{ route('tickets.create', 'mantenimiento') }}" class="mt-6 inline-flex h-12 w-full items-center justify-center gap-2 rounded-[var(--radius-button)] bg-[var(--primary)] px-4 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]" aria-label="Agendar Mantenimiento">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-3-3v3m-7 4h14M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
                                </svg>
                                Programar Cita
                            </a>
                        </article>

                        <article class="mx-auto flex w-full max-w-[360px] min-h-[320px] flex-col justify-between rounded-[var(--radius-card)] border border-[var(--border)] bg-white p-7 shadow-[0_2px_10px_rgba(16,24,40,0.06)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_10px_30px_rgba(15,23,42,0.12)] focus-within:outline focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-[var(--focus)]" aria-labelledby="card-hardware-title">
                            <div>
                                <span class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[rgba(47,107,255,0.1)]">
                                    <svg class="h-10 w-10 text-[var(--primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M8 12v6m8-6v6M5 18h14a1 1 0 001-1V7H4v10a1 1 0 001 1z" />
                                    </svg>
                                </span>
                                <h3 id="card-hardware-title" class="text-[20px] font-semibold leading-[1.2] text-[var(--text)]">Reportar Hardware</h3>
                                <p class="mt-3 text-[15px] leading-[1.5] text-[var(--muted)]" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
                                    Computadora, impresora u otro equipo con fallas. Abre un ticket.
                                </p>
                            </div>
                            <a href="{{ route('tickets.create', 'hardware') }}" class="mt-6 inline-flex h-12 w-full items-center justify-center gap-2 rounded-[var(--radius-button)] bg-[var(--primary)] px-4 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]" aria-label="Reportar Hardware">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M8 12v6m8-6v6M5 18h14a1 1 0 001-1V7H4v10a1 1 0 001 1z" />
                                </svg>
                                Reportar Falla
                            </a>
                        </article>
                    </div>
                </section>

                <section class="mt-14 rounded-[var(--radius-card)] border border-[var(--border)] bg-white p-8 shadow-[0_2px_10px_rgba(16,24,40,0.06)]" aria-labelledby="help-center">
                    <div class="max-w-3xl">
                        <h2 id="help-center" class="text-[24px] font-semibold leading-[1.2] text-[var(--text)]">¿Necesitas ayuda?</h2>
                        <p class="mt-3 text-[16px] leading-[1.5] text-[var(--muted)]">
                            Consulta el manual paso a paso o contacta directamente al equipo de TI para resolver dudas específicas.
                        </p>
                    </div>

                    <form action="{{ route('help.public') }}" method="GET" class="mt-6 flex flex-col gap-3 md:flex-row">
                        <label for="help-search" class="sr-only">Buscar en el manual</label>
                        <div class="relative flex-1">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--muted)]" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5a6 6 0 104.472 10.028l3.25 3.25" />
                                </svg>
                            </span>
                            <input id="help-search" name="q" type="search" placeholder="Busca en el manual…" class="h-12 w-full rounded-[var(--radius-button)] border border-[var(--border)] bg-white pl-12 pr-16 text-sm text-[var(--text)] placeholder:text-[var(--muted)] focus:border-[var(--primary)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]" aria-label="Buscar en el manual" />
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 rounded-md border border-[var(--border)] bg-[var(--surface)] px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-[var(--muted)]">/</span>
                        </div>
                        <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-[var(--radius-button)] bg-[var(--primary)] px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]" aria-label="Buscar en el manual">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5a6 6 0 104.472 10.028l3.25 3.25" />
                            </svg>
                            Buscar
                        </button>
                    </form>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('help.public') }}" class="inline-flex h-12 flex-1 items-center justify-center gap-2 rounded-[var(--radius-button)] bg-[var(--primary)] px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[var(--primary-hover)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]" aria-label="Ver Manual">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5c1.5-.667 3-.667 4.5 0S12 5.667 13.5 5 16.5 4.333 18 5v13c-1.5-.667-3-.667-4.5 0s-3 1.333-4.5.667S7 18.667 5.5 19 4 19.333 4 20V5z" />
                            </svg>
                            Ver Manual
                        </a>
                        <a href="mailto:soporte@estrategiaeinnovacion.com.mx" class="inline-flex h-12 flex-1 items-center justify-center gap-2 rounded-[var(--radius-button)] border border-[var(--border)] px-5 text-sm font-semibold text-[var(--text)] transition-colors duration-200 hover:border-[var(--primary)] hover:text-[var(--primary)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--focus)]" aria-label="Contacto Directo">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6m-9 6v6" />
                            </svg>
                            Contacto Directo
                        </a>
                    </div>
                </section>
            @endauth
        </div>
    </main>

    <footer class="mt-16 border-t border-[var(--border)] bg-white">
        <div class="mx-auto max-w-[1280px] px-6 py-6 text-center text-sm text-[var(--muted)]">
            &copy; {{ date('Y') }} E&I - Comercio Exterior, Logística y Tecnología. Todos los derechos reservados.
        </div>
    </footer>
@endsection
