<?php

namespace App\Http\Controllers;

use App\Models\ComputerProfile;
use App\Models\MaintenanceSlot;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function availability(Request $request): JsonResponse
    {
        $month = $request->query('month');
        try {
            $start = $month ? Carbon::createFromFormat('Y-m', $month)->startOfMonth() : now()->startOfMonth();
        } catch (\Exception $e) {
            $start = now()->startOfMonth();
        }

        $end = $start->copy()->endOfMonth();

        $slots = MaintenanceSlot::active()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get()
            ->groupBy(fn ($slot) => $slot->date->toDateString())
            ->map(function ($daySlots) {
                $totalCapacity = $daySlots->sum('capacity');
                $booked = $daySlots->sum('booked_count');
                $available = max(0, $totalCapacity - $booked);

                if ($totalCapacity === 0) {
                    $status = 'empty';
                } elseif ($available === 0) {
                    $status = 'full';
                } elseif ($booked === 0) {
                    $status = 'available';
                } else {
                    $status = 'partial';
                }

                return [
                    'date' => $daySlots->first()->date->toDateString(),
                    'total_slots' => $daySlots->count(),
                    'total_capacity' => $totalCapacity,
                    'booked' => $booked,
                    'available' => $available,
                    'status' => $status,
                ];
            })
            ->values();

        return response()->json([
            'month' => $start->format('Y-m'),
            'days' => $slots,
        ]);
    }

    public function slots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $slots = MaintenanceSlot::active()
            ->whereDate('date', $request->query('date'))
            ->orderBy('start_time')
            ->get()
            ->map(function (MaintenanceSlot $slot) {
                $start = Carbon::parse($slot->start_time instanceof Carbon ? $slot->start_time->format('H:i:s') : $slot->start_time);
                $end = Carbon::parse($slot->end_time instanceof Carbon ? $slot->end_time->format('H:i:s') : $slot->end_time);

                return [
                    'id' => $slot->id,
                    'start' => $start->format('H:i'),
                    'end' => $end->format('H:i'),
                    'label' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                    'available' => $slot->available_capacity,
                    'capacity' => $slot->capacity,
                    'status' => $slot->available_capacity === 0 ? 'full' : ($slot->available_capacity === $slot->capacity ? 'available' : 'partial'),
                ];
            });

        return response()->json([
            'date' => $request->query('date'),
            'slots' => $slots,
        ]);
    }

    public function adminIndex(): View
    {
        $slots = MaintenanceSlot::orderBy('date')->orderBy('start_time')->get();

        $groupedSlots = $slots->groupBy(fn ($slot) => Carbon::parse($slot->date)->format('Y-m-d'));

        return view('admin.maintenance.index', [
            'groupedSlots' => $groupedSlots,
        ]);
    }

    public function storeSlot(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:10',
        ]);

        MaintenanceSlot::create([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'capacity' => $validated['capacity'],
            'booked_count' => 0,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Horario de mantenimiento creado correctamente.');
    }

    public function updateSlot(Request $request, MaintenanceSlot $slot): RedirectResponse
    {
        $validated = $request->validate([
            'capacity' => 'required|integer|min:1|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $slot->capacity = $validated['capacity'];
        $slot->is_active = $request->boolean('is_active', true);
        if ($slot->booked_count > $slot->capacity) {
            $slot->booked_count = $slot->capacity;
        }
        $slot->save();

        return redirect()->back()->with('success', 'Horario actualizado correctamente.');
    }

    public function destroySlot(MaintenanceSlot $slot): RedirectResponse
    {
        if ($slot->booked_count > 0) {
            $slot->update(['is_active' => false]);

            return redirect()->back()->with('success', 'El horario tiene reservaciones activas. Se deshabilitó en lugar de eliminarse.');
        }

        $slot->delete();

        return redirect()->back()->with('success', 'Horario eliminado correctamente.');
    }

    public function computersIndex(): View
    {
        $profiles = ComputerProfile::with('ticket')->orderBy('identifier')->get();

        return view('admin.maintenance.computers', [
            'profiles' => $profiles,
        ]);
    }

    public function updateComputerLoan(Request $request, ComputerProfile $profile): RedirectResponse
    {
        $validated = $request->validate([
            'is_loaned' => 'sometimes|boolean',
            'loaned_to_name' => 'nullable|string|max:255',
            'loaned_to_email' => 'nullable|email|max:255',
        ]);

        $isLoaned = $request->boolean('is_loaned');
        $profile->is_loaned = $isLoaned;
        if ($isLoaned) {
            $profile->loaned_to_name = $validated['loaned_to_name'] ?? null;
            $profile->loaned_to_email = $validated['loaned_to_email'] ?? null;
        } else {
            $profile->loaned_to_name = null;
            $profile->loaned_to_email = null;
        }
        $profile->save();

        return redirect()->back()->with('success', 'Estado de préstamo actualizado correctamente.');
    }
}
