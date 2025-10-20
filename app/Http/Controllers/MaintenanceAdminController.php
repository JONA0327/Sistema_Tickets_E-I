<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceEquipmentRecord;
use App\Models\MaintenanceTimeSlot;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceAdminController extends Controller
{
    public function index(): View
    {
        $slots = MaintenanceTimeSlot::withCount('tickets')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $tickets = Ticket::with(['maintenanceSlot'])
            ->where('tipo_problema', 'mantenimiento')
            ->orderBy('estado')
            ->orderByDesc('created_at')
            ->get();

        $records = MaintenanceEquipmentRecord::with(['ticket'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.maintenance.index', compact('slots', 'tickets', 'records'));
    }

    public function storeSlot(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
            'is_open' => 'nullable|boolean',
        ]);

        MaintenanceTimeSlot::create([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'] ?? null,
            'capacity' => $validated['capacity'],
            'notes' => $validated['notes'] ?? null,
            'is_open' => $request->boolean('is_open', true),
        ]);

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Horario de mantenimiento creado correctamente.');
    }

    public function updateSlot(Request $request, MaintenanceTimeSlot $slot): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
            'is_open' => 'nullable|boolean',
        ]);

        if ($slot->tickets()->count() > $validated['capacity']) {
            return redirect()->back()->withErrors([
                'capacity' => 'La capacidad no puede ser menor al número de tickets ya asignados a este horario.',
            ]);
        }

        $slot->update([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'] ?? null,
            'capacity' => $validated['capacity'],
            'notes' => $validated['notes'] ?? null,
            'is_open' => $request->boolean('is_open', true),
        ]);

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Horario actualizado correctamente.');
    }

    public function destroySlot(MaintenanceTimeSlot $slot): RedirectResponse
    {
        if ($slot->tickets()->exists()) {
            return redirect()->back()->withErrors([
                'slot' => 'No se puede eliminar un horario que ya tiene mantenimientos asignados.',
            ]);
        }

        $slot->delete();

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Horario eliminado correctamente.');
    }

    public function updateLoanStatus(Request $request, MaintenanceEquipmentRecord $record): RedirectResponse
    {
        $validated = $request->validate([
            'prestado' => 'required|boolean',
            'prestado_a_nombre' => 'nullable|string|max:255',
            'prestado_a_correo' => 'nullable|email|max:255',
        ]);

        if ((bool) $validated['prestado']) {
            $prestamoData = $request->validate([
                'prestado_a_nombre' => 'required|string|max:255',
                'prestado_a_correo' => 'required|email|max:255',
            ]);

            $validated = array_merge($validated, $prestamoData);
        }

        $record->update([
            'prestado' => (bool) $validated['prestado'],
            'prestado_a_nombre' => $validated['prestado'] ? $validated['prestado_a_nombre'] : null,
            'prestado_a_correo' => $validated['prestado'] ? $validated['prestado_a_correo'] : null,
        ]);

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Estado de préstamo actualizado.');
    }
}
