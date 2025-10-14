<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar si el usuario es administrador (por rol)
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('welcome')->with('info', 'Esta sección es solo para administradores. Desde aquí puedes crear y gestionar tus tickets.');
        }

        return $next($request);
    }
}
