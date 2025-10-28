@extends('layouts.master')

@section('title', '🆘 Manual de Ayuda - Sistema IT')

@section('content')
<main class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3.063h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">🆘 Manual de Ayuda</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Guía completa para usar el Sistema de Tickets IT. Encuentra respuestas a las preguntas más frecuentes y aprende a utilizar todas las funcionalidades.
        </p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg text-white p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-blue-100">Secciones Disponibles</p>
                    <p class="text-3xl font-bold">{{ $sections->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg text-white p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-green-100">Información Útil</p>
                    <p class="text-3xl font-bold">24/7</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg text-white p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-purple-100">Actualizado</p>
                    <p class="text-3xl font-bold">
                        @if($sections->count() > 0)
                            {{ $sections->max('updated_at')->format('d/m') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($sections->count() > 0)
        <!-- Índice de Contenidos -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    📋 Índice de Contenidos
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($sections as $section)
                        <a href="#section-{{ $section->id }}" 
                           class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 group">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 group-hover:bg-blue-200">
                                <span class="text-sm font-medium text-blue-600">{{ $section->section_order }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600">{{ $section->title }}</p>
                                <p class="text-xs text-gray-500">{{ Str::limit(strip_tags($section->content), 60) }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Secciones del Manual -->
        <div class="space-y-8">
            @foreach($sections as $index => $section)
                <div id="section-{{ $section->id }}" class="bg-white rounded-lg shadow-lg border border-gray-200 scroll-mt-20">
                    <!-- Header de la Sección -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-lg font-bold text-white">{{ $section->section_order }}</span>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $section->title }}</h2>
                                    <p class="text-sm text-gray-500">
                                        Sección {{ $section->section_order }} de {{ $sections->count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="hidden sm:flex items-center space-x-2">
                                <span class="text-xs text-gray-500">
                                    Actualizado: {{ $section->updated_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido de la Sección -->
                    <div class="px-8 py-6">
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br($section->getProcessedContent()) !!}
                        </div>
                    </div>

                    <!-- Footer de la Sección -->
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Actualizado el {{ $section->updated_at->format('d/m/Y \a \l\a\s H:i') }}
                            </div>
                            @if(!$loop->last)
                                <a href="#section-{{ $sections[$index + 1]->id }}" 
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    Siguiente sección
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botón para volver arriba -->
        <div class="mt-12 text-center">
            <a href="#top" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                ⬆️ Volver al inicio
            </a>
        </div>
    @else
        <!-- Estado vacío -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 py-12">
            <div class="text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-2xl font-medium text-gray-900 mb-4">Manual en construcción</h3>
                <p class="text-lg text-gray-500 mb-8 max-w-md mx-auto">
                    El manual de ayuda está siendo preparado por nuestro equipo. Pronto estará disponible con toda la información que necesitas.
                </p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-lg mx-auto">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-left">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">💡 Mientras tanto, puedes:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Crear tickets de soporte desde el panel principal</li>
                                <li>• Consultar tus tickets existentes en "Mis Tickets"</li>
                                <li>• Explorar el inventario disponible</li>
                                <li>• Contactar al administrador para ayuda directa</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Información adicional -->
    <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3.063h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-medium text-blue-900 mb-2">¿No encuentras lo que buscas?</h3>
                <p class="text-blue-800 mb-4">
                    Si no encuentras la información que necesitas en este manual, no dudes en contactarnos directamente.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('tickets.create', 'software') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Ticket de Soporte
                    </a>
                    <a href="mailto:soporte@estrategiaeinnovacion.com.mx" 
                       class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-blue-600 text-sm font-medium rounded-lg border border-blue-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Enviar Email Directo
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Smooth scrolling para los enlaces del índice
document.addEventListener('DOMContentLoaded', function() {
    const indexLinks = document.querySelectorAll('a[href^="#section-"]');
    
    indexLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Highlight active section while scrolling
    const sections = document.querySelectorAll('[id^="section-"]');
    const indexItems = document.querySelectorAll('a[href^="#section-"]');

    function highlightActiveSection() {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.pageYOffset >= sectionTop - 100) {
                current = section.getAttribute('id');
            }
        });

        indexItems.forEach(item => {
            item.classList.remove('bg-blue-50', 'border-blue-200');
            if (item.getAttribute('href') === '#' + current) {
                item.classList.add('bg-blue-50', 'border-blue-200');
            }
        });
    }

    window.addEventListener('scroll', highlightActiveSection);
});
</script>

@endsection