<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de préstamos de inventario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <p class="font-medium">{{ session('status') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Registro de préstamos</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Aquí se mostrarán los préstamos activos y su estado de devolución.
                            </p>
                        </div>
                        <a
                            href="{{ route('prestamos.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150"
                        >
                            Registrar préstamo
                        </a>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <p class="text-sm text-gray-500">
                            Aún no hay préstamos registrados en esta vista demostrativa. Utiliza el botón anterior para crear uno nuevo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
