@extends('layouts.master')

@section('title', 'Editar Usuario - ' . $user->name)

@section('content')
<main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('users.show', $user) }}" 
               class="text-blue-600 hover:text-blue-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Usuario</h1>
                <p class="text-gray-600 mt-1">Solo se permite modificar email y contraseña para preservar el historial</p>
            </div>
        </div>
    </div>

    <!-- Información del Usuario (Solo Lectura) -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-lg font-bold text-white">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-600">
                    ID: #{{ $user->id }} | 
                    Rol: {{ $user->role === 'admin' ? '👑 Administrador' : '👤 Usuario' }} |
                    Estado: {{ $user->status === 'approved' ? '✅ Aprobado' : ($user->status === 'pending' ? '⏳ Pendiente' : '❌ Rechazado') }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    🔒 <strong>Nombre y rol protegidos</strong> - Se mantienen para preservar la integridad del historial de tickets y préstamos
                </p>
            </div>
        </div>
    </div>

    <!-- Formulario de Edición -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="p-8">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        📧 Correo Electrónico *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Debe terminar en @estrategiaeinnovacion.com.mx
                    </p>
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        🔐 Nueva Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Dejar en blanco si no deseas cambiar la contraseña. Mínimo 8 caracteres.
                    </p>
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        🔐 Confirmar Nueva Contraseña
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">
                        Repite la nueva contraseña para confirmarla
                    </p>
                </div>

                <!-- Advertencia de Integridad -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Protección de Integridad de Datos</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                El <strong>nombre</strong> y <strong>rol</strong> del usuario no pueden modificarse para mantener la consistencia del historial.
                                Todos los tickets y préstamos permanecen vinculados al ID único del usuario (#{{ $user->id }}).
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        💾 Guardar Cambios
                    </button>
                    <a href="{{ route('users.show', $user) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ Información del Sistema</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">🆔 ID de Usuario:</span>
                <span class="font-medium text-gray-900 ml-2">#{{ $user->id }}</span>
            </div>
            <div>
                <span class="text-gray-600">📅 Fecha de Registro:</span>
                <span class="font-medium text-gray-900 ml-2">{{ $user->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span class="text-gray-600">🎫 Tickets Creados:</span>
                <span class="font-medium text-gray-900 ml-2">{{ $user->tickets()->count() }}</span>
            </div>
            <div>
                <span class="text-gray-600">📦 Préstamos Realizados:</span>
                <span class="font-medium text-gray-900 ml-2">{{ $user->prestamosInventario()->count() }}</span>
            </div>
        </div>
        <p class="mt-4 text-xs text-gray-500">
            Todos los datos del historial permanecen intactos y vinculados al ID único del usuario.
        </p>
    </div>
</main>
@endsection