@extends('layouts.master')

@section('title', '🆘 Manual de Ayuda - Sistema IT')

@section('content')
<main class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-12 text-center">
        <div class="mb-6 inline-flex h-20 w-20 items-center justify-center">
                <x-ui.icon-box name="lifebuoy" boxClass="h-20 w-20 rounded-3xl" iconClass="h-10 w-10" />
            </div>
        <h1 class="mb-4 text-4xl font-bold text-gray-900">Manual de Ayuda</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Guía completa para usar el Sistema de Tickets IT. Encuentra respuestas a las preguntas más frecuentes y aprende a utilizar todas las funcionalidades.
        </p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-ui.icon name="squares-2x2" class="h-8 w-8" />
                </div>
                <div class="ml-5">
                    <p class="text-blue-100">Secciones Disponibles</p>
                    <p class="text-3xl font-bold">{{ $sections->count() }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-gradient-to-r from-green-500 to-green-600 p-6 text-white shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-ui.icon name="information-circle" class="h-8 w-8" />
                </div>
                <div class="ml-5">
                    <p class="text-green-100">Información Útil</p>
                    <p class="text-3xl font-bold">24/7</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-ui.icon name="clock" class="h-8 w-8" />
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
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="flex items-center text-xl font-semibold text-gray-900">
                    <x-ui.icon name="book-open" class="mr-2 h-5 w-5 text-blue-600" />
                    Índice de Contenidos
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
                            <x-ui.icon name="chevron-right" class="h-4 w-4 text-gray-400 transition-colors duration-200 group-hover:text-blue-600" />
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
                                <x-ui.icon name="clock" class="mr-1 h-4 w-4 text-gray-400" />
                                Actualizado el {{ $section->updated_at->format('d/m/Y \a \l\a\s H:i') }}
                            </div>
                            @if(!$loop->last)
                                <a href="#section-{{ $sections[$index + 1]->id }}"
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    Siguiente sección
                                    <x-ui.icon name="chevron-right" class="ml-1 h-4 w-4" />
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
                <x-ui.icon name="arrow-up" class="mr-2 h-4 w-4" />
                Volver al inicio
            </a>
        </div>
    @else
        <!-- Estado vacío -->
        <div class="rounded-lg border border-gray-200 bg-white py-12 shadow-lg">
            <div class="text-center">
                <x-ui.icon name="squares-2x2" class="mx-auto mb-6 h-24 w-24 text-gray-300" />
                <h3 class="text-2xl font-medium text-gray-900 mb-4">Manual en construcción</h3>
                <p class="text-lg text-gray-500 mb-8 max-w-md mx-auto">
                    El manual de ayuda está siendo preparado por nuestro equipo. Pronto estará disponible con toda la información que necesitas.
                </p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 max-w-lg mx-auto">
                    <div class="flex items-start">
                        <x-ui.icon name="information-circle" class="mt-1 mr-3 h-6 w-6 flex-shrink-0 text-blue-500" />
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
    <div class="mt-12 rounded-lg border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-6">
        <div class="flex items-start">
            <x-ui.icon-box name="lifebuoy" boxClass="h-9 w-9 rounded-lg" iconClass="h-5 w-5" class="mt-0 mr-3 flex-shrink-0" />
            <div>
                <h3 class="text-lg font-medium text-blue-900 mb-2">¿No encuentras lo que buscas?</h3>
                <p class="text-blue-800 mb-4">
                    Si no encuentras la información que necesitas en este manual, no dudes en contactarnos directamente.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('tickets.create', 'software') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <x-ui.icon name="clipboard-document-list" class="mr-2 h-4 w-4" />
                        Crear Ticket de Soporte
                    </a>
                    <a href="mailto:soporte@estrategiaeinnovacion.com.mx"
                       class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-blue-600 text-sm font-medium rounded-lg border border-blue-200 transition-colors">
                        <x-ui.icon name="envelope" class="mr-2 h-4 w-4" />
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