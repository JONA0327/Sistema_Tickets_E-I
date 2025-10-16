<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalles del préstamo #{{ $prestamoId }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <p class="font-medium">{{ session('status') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <p class="text-sm text-gray-500">
                        Esta vista muestra información referencial del préstamo seleccionado. En una implementación completa
                        aquí se detallarían los activos prestados, el solicitante y el estado de devolución.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Solicitante</h3>
                            <p class="mt-1 text-lg text-gray-900">Dato demostrativo</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Fecha estimada de devolución</h3>
                            <p class="mt-1 text-lg text-gray-900">Pendiente de definir</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <a
                            href="{{ route('prestamos.edit', $prestamoId) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Editar préstamo
                        </a>
                        <form method="POST" action="{{ route('prestamos.destroy', $prestamoId) }}">
                            @csrf
                            @method('DELETE')

                            <x-danger-button onclick="return confirm('¿Deseas eliminar este préstamo?')">
                                Eliminar
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
