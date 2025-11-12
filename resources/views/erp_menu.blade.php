@extends('layouts.master')

@section('title', 'ERP Empresarial - E&I Tecnología')

@section('content')
    <main class="relative overflow-hidden bg-gradient-to-br from-white via-blue-50 to-blue-100 min-h-screen">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-32 -left-20 w-96 h-96 bg-blue-200/40 blur-3xl rounded-full"></div>
            <div class="absolute top-40 -right-24 w-96 h-96 bg-blue-300/30 blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-32 bg-gradient-to-t from-white"></div>
        </div>

        <div class="relative max-w-6xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 text-blue-700 px-4 py-1 text-xs font-semibold uppercase tracking-wide shadow-sm">
                    Próxima expansión ERP
                </span>
                <h1 class="mt-6 text-3xl sm:text-4xl font-bold text-slate-900">Menú Central del ERP</h1>
                <p class="mt-4 max-w-2xl mx-auto text-sm sm:text-base text-slate-600">
                    Explora las diferentes áreas de la empresa. Este menú central agrupará los procesos clave de Recursos Humanos, Logística y Soporte Técnico.
                </p>
            </div>

            <section class="relative">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                        $areas = [
                            [
                                'title' => 'Recursos Humanos',
                                'description' => 'Gestión de personal, reclutamiento, control de asistencias y desarrollo organizacional.',
                                'icon' => 'M12 11c1.657 0 3-1.567 3-3.5S13.657 4 12 4 9 5.567 9 7.5 10.343 11 12 11zm0 2c-2.21 0-4 1.343-4 3v1h8v-1c0-1.657-1.79-3-4-3z',
                                'route' => '#recursos-humanos'
                            ],
                            [
                                'title' => 'Logística',
                                'description' => 'Control de inventarios, seguimiento de envíos y coordinación de la cadena de suministro.',
                                'icon' => 'M3 7h18M3 7a2 2 0 012-2h14a2 2 0 012 2M3 7v9a2 2 0 002 2h14a2 2 0 002-2V7m-5 6h.01M7 13h5',
                                'route' => '#logistica'
                            ],
                            [
                                'title' => 'Soporte Técnico',
                                'description' => 'Administración de tickets, mantenimiento preventivo y soporte a toda la organización.',
                                'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                'route' => route('welcome')
                            ],
                        ];
                    @endphp

                    @foreach ($areas as $area)
                        <div class="relative overflow-hidden rounded-3xl border border-blue-100/80 bg-white/90 backdrop-blur shadow-lg shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                            <div class="absolute -top-20 -right-16 w-40 h-40 bg-gradient-to-br from-blue-200/50 to-transparent blur-3xl"></div>
                            <div class="relative p-8 flex flex-col h-full">
                                <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 shadow-inner shadow-white/40 mx-auto mb-6">
                                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area['icon'] }}"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-center text-slate-900 mb-3">
                                    {{ $area['title'] }}
                                </h2>
                                <p class="text-center text-slate-600 leading-relaxed mb-8">
                                    {{ $area['description'] }}
                                </p>
                                <a href="{{ $area['route'] }}" class="mt-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:from-blue-700 hover:to-blue-800">
                                    <span>Ingresar</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </main>
@endsection
