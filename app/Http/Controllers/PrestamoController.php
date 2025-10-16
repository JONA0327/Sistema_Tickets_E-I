<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /**
     * Muestra el listado general de préstamos de inventario.
     */
    public function index()
    {
        return view('prestamos.index');
    }

    /**
     * Muestra el formulario para registrar un nuevo préstamo.
     */
    public function create()
    {
        return view('prestamos.create');
    }

    /**
     * Procesa el formulario de creación de un préstamo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'solicitante' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_devolucion_estimada' => ['nullable', 'date'],
        ]);

        return redirect()
            ->route('prestamos.index')
            ->with('status', 'Préstamo registrado correctamente (vista demostrativa).');
    }

    /**
     * Muestra la información resumida de un préstamo específico.
     */
    public function show(string $prestamo)
    {
        return view('prestamos.show', [
            'prestamoId' => $prestamo,
        ]);
    }

    /**
     * Muestra el formulario para editar un préstamo específico.
     */
    public function edit(string $prestamo)
    {
        return view('prestamos.edit', [
            'prestamoId' => $prestamo,
        ]);
    }

    /**
     * Procesa la actualización de un préstamo existente.
     */
    public function update(Request $request, string $prestamo)
    {
        $request->validate([
            'solicitante' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_devolucion_estimada' => ['nullable', 'date'],
        ]);

        return redirect()
            ->route('prestamos.show', $prestamo)
            ->with('status', 'Préstamo actualizado correctamente (vista demostrativa).');
    }

    /**
     * Elimina (de forma demostrativa) un préstamo registrado.
     */
    public function destroy(string $prestamo)
    {
        return redirect()
            ->route('prestamos.index')
            ->with('status', 'Préstamo eliminado correctamente (vista demostrativa).');
    }
}
