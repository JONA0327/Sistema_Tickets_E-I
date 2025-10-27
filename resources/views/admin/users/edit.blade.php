@extends('layouts.master')

@section('title', 'Editar Usuario - ' . $user->name)

@section('content')
<main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.users.show', $user) }}" 
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
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        👤 Nombre Completo *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Nombre completo del usuario como aparecerá en el sistema
                    </p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email_prefix" class="block text-sm font-medium text-gray-700 mb-2">
                        📧 Correo Electrónico *
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               id="email_prefix" 
                               name="email_prefix" 
                               value="{{ old('email_prefix', explode('@', $user->email)[0]) }}" 
                               required
                               pattern="[a-zA-Z0-9._-]+"
                               title="Solo letras, números, puntos, guiones y guiones bajos"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email_prefix') border-red-500 @enderror @error('email') border-red-500 @enderror">
                        <span class="text-gray-500 font-medium px-2">@estrategiaeinnovacion.com.mx</span>
                    </div>
                    @error('email_prefix')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Solo puedes cambiar la parte antes del @. El dominio siempre será @estrategiaeinnovacion.com.mx
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
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Información de Edición</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                Puedes editar el <strong>nombre</strong> y <strong>correo</strong> del usuario. 
                                El <strong>rol</strong> no puede modificarse por seguridad.
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
                    <a href="{{ route('admin.users.show', $user) }}" 
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailPrefix = document.getElementById('email_prefix');
    const emailDisplay = document.createElement('div');
    emailDisplay.className = 'mt-2 text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded border';
    emailDisplay.innerHTML = '<strong>📧 Email completo:</strong> <span id="full-email" class="font-mono text-blue-600"></span>';
    emailPrefix.parentNode.appendChild(emailDisplay);
    
    function updateEmailDisplay() {
        const prefix = emailPrefix.value.trim() || '[usuario]';
        const fullEmail = prefix + '@estrategiaeinnovacion.com.mx';
        document.getElementById('full-email').textContent = fullEmail;
        
        // Cambiar color si es válido
        const span = document.getElementById('full-email');
        if (prefix !== '[usuario]' && /^[a-zA-Z0-9._-]+$/.test(prefix)) {
            span.className = 'font-mono text-green-600';
        } else {
            span.className = 'font-mono text-blue-600';
        }
    }
    
    // Actualizar en tiempo real
    emailPrefix.addEventListener('input', updateEmailDisplay);
    
    // Mostrar email inicial
    updateEmailDisplay();
    
    // Validación en tiempo real con mejor UX
    emailPrefix.addEventListener('input', function() {
        const value = this.value.trim();
        const validPattern = /^[a-zA-Z0-9._-]+$/;
        
        // Remover clases anteriores
        this.classList.remove('border-red-500', 'border-green-500');
        
        if (value) {
            if (!validPattern.test(value)) {
                this.setCustomValidity('Solo se permiten letras, números, puntos, guiones y guiones bajos');
                this.classList.add('border-red-500');
            } else {
                this.setCustomValidity('');
                this.classList.add('border-green-500');
            }
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Validación al enviar el formulario
    const form = emailPrefix.closest('form');
    form.addEventListener('submit', function(e) {
        const prefix = emailPrefix.value.trim();
        if (!prefix) {
            e.preventDefault();
            emailPrefix.focus();
            alert('Por favor, ingresa la parte del correo antes del @');
            return false;
        }
        
        if (!/^[a-zA-Z0-9._-]+$/.test(prefix)) {
            e.preventDefault();
            emailPrefix.focus();
            alert('El correo solo puede contener letras, números, puntos, guiones y guiones bajos');
            return false;
        }
    });
});
</script>

@endsection