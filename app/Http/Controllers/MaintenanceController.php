<?php

namespace App\Http\Controllers;

use App\Models\ComputerProfile;
use App\Models\MaintenanceSlot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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

        $now = Carbon::now('America/Mexico_City');

        $slots = MaintenanceSlot::active()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get()
            ->groupBy(fn ($slot) => $slot->date->toDateString())
            ->map(function ($daySlots) use ($now) {
                $date = $daySlots->first()->date->toDateString();
                $dayDate = Carbon::parse($date, 'America/Mexico_City');

                // Mantener únicamente los horarios futuros respecto a la hora de México
                $futureSlots = $daySlots->filter(fn (MaintenanceSlot $slot) => $slot->start_date_time->greaterThan($now));

                $totalCapacity = $futureSlots->sum('capacity');
                $booked = $futureSlots->sum('booked_count');
                $available = max(0, $totalCapacity - $booked);
                $availableSlots = $futureSlots->filter(fn (MaintenanceSlot $slot) => $slot->available_capacity > 0)->count();

                if ($dayDate->copy()->endOfDay()->lessThanOrEqualTo($now) || $futureSlots->isEmpty()) {
                    $status = 'past';
                    $available = 0;
                } elseif ($available === 0) {
                    $status = 'full';
                } elseif ($booked === 0) {
                    $status = 'available';
                } else {
                    $status = 'partial';
                }

                return [
                    'date' => $date,
                    'total_slots' => $daySlots->count(),
                    'total_capacity' => $totalCapacity,
                    'booked' => $booked,
                    'available' => $available,
                    'available_slots' => $availableSlots,
                    'is_past' => $status === 'past',
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

        $now = Carbon::now('America/Mexico_City');

        $slots = MaintenanceSlot::active()
            ->whereDate('date', $request->query('date'))
            ->orderBy('start_time')
            ->get()
            ->map(function (MaintenanceSlot $slot) use ($now) {
                $start = $slot->start_date_time;
                $end = $slot->end_date_time;

                $isPast = $start->lessThanOrEqualTo($now);
                $availableCapacity = $isPast ? 0 : $slot->available_capacity;

                if ($isPast) {
                    $status = 'past';
                } elseif ($availableCapacity === 0) {
                    $status = 'full';
                } elseif ($availableCapacity === $slot->capacity) {
                    $status = 'available';
                } else {
                    $status = 'partial';
                }

                return [
                    'id' => $slot->id,
                    'start' => $start->format('H:i'),
                    'end' => $end->format('H:i'),
                    'label' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                    'available' => $availableCapacity,
                    'capacity' => $slot->capacity,
                    'status' => $status,
                    'is_past' => $isPast,
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
            'componentOptions' => $this->getReplacementComponentOptions(),
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

    public function storeBulkSlots(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array|min:1',
            'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'bulk_start_time' => 'required|date_format:H:i',
            'bulk_end_time' => 'required|date_format:H:i|after:bulk_start_time',
            'total_capacity' => 'required|integer|min:1|max:20',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $selectedDays = $validated['days'];
        $startTime = $validated['bulk_start_time'];
        $endTime = $validated['bulk_end_time'];
        $totalCapacity = $validated['total_capacity'];

        // Mapeo de días en inglés a números
        $dayMap = [
            'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4,
            'friday' => 5, 'saturday' => 6, 'sunday' => 0
        ];

        // Calcular duración total en minutos
        $startTimeObj = Carbon::createFromFormat('H:i', $startTime);
        $endTimeObj = Carbon::createFromFormat('H:i', $endTime);
        
        // Usar diffInMinutes con parámetro absoluto para obtener valor positivo
        $totalMinutes = abs($endTimeObj->diffInMinutes($startTimeObj));

        // Calcular duración por slot de forma simple y directa
        $slotDuration = floor($totalMinutes / $totalCapacity);
        
        // Verificar que cada slot tenga al menos 15 minutos
        if ($slotDuration < 15) {
            $maxCapacity = floor($totalMinutes / 15);
            return redirect()->back()->withErrors([
                'total_capacity' => "Tiempo insuficiente. Con {$totalMinutes} minutos disponibles, máximo {$maxCapacity} slots de 15 minutos cada uno."
            ])->withInput();
        }

        // La duración ya está calculada y validada arriba

        $createdSlots = 0;
        $currentDate = $startDate->copy();

        // Iterar por cada día en el rango
        while ($currentDate <= $endDate) {
            $dayOfWeek = $currentDate->dayOfWeek;

            // Verificar si este día está seleccionado
            foreach ($selectedDays as $selectedDay) {
                if ($dayMap[$selectedDay] === $dayOfWeek) {
                    // Crear slots para este día
                    $currentSlotTime = Carbon::createFromFormat('H:i', $startTime);

                    for ($i = 0; $i < $totalCapacity; $i++) {
                        $slotStart = $currentSlotTime->copy();
                        $slotEnd = $currentSlotTime->copy()->addMinutes($slotDuration);

                        // Verificar que no exceda la hora de fin
                        if ($slotEnd > $endTimeObj) {
                            $slotEnd = $endTimeObj->copy();
                        }

                        // Verificar si ya existe un slot en esta fecha y horario
                        $exists = MaintenanceSlot::where('date', $currentDate->toDateString())
                            ->where('start_time', $slotStart->format('H:i'))
                            ->where('end_time', $slotEnd->format('H:i'))
                            ->exists();

                        if (!$exists) {
                            MaintenanceSlot::create([
                                'date' => $currentDate->toDateString(),
                                'start_time' => $slotStart->format('H:i'),
                                'end_time' => $slotEnd->format('H:i'),
                                'capacity' => 1, // Cada slot individual tiene capacidad 1
                                'booked_count' => 0,
                                'is_active' => true,
                            ]);

                            $createdSlots++;
                        }

                        $currentSlotTime->addMinutes($slotDuration);

                        // Si llegamos al tiempo límite, salir del bucle
                        if ($currentSlotTime >= $endTimeObj) {
                            break;
                        }
                    }
                    break; // Solo procesar una vez por día
                }
            }

            $currentDate->addDay();
        }

        return redirect()->back()->with('success', "Se crearon {$createdSlots} horarios de mantenimiento correctamente.");
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
        $result = $this->cancelBookingsAndDeleteSlot($slot);

        $message = 'Horario eliminado correctamente.';
        if ($result['cancelled_bookings'] > 0) {
            $message .= ' Se cancelaron ' . $result['cancelled_bookings'] . ' reservación(es) asociada(s).';
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroyPastSlots(): RedirectResponse
    {
        $today = Carbon::now('America/Mexico_City')->toDateString();

        $slots = MaintenanceSlot::whereDate('date', '<', $today)
            ->orderBy('date')
            ->get();

        if ($slots->isEmpty()) {
            return redirect()->back()->with('success', 'No hay horarios pasados para eliminar.');
        }

        $deletedSlots = 0;
        $cancelledBookings = 0;

        foreach ($slots as $slot) {
            $result = $this->cancelBookingsAndDeleteSlot($slot);
            $deletedSlots++;
            $cancelledBookings += $result['cancelled_bookings'];
        }

        $message = 'Se eliminaron ' . $deletedSlots . ' horario(s) pasado(s).';
        if ($cancelledBookings > 0) {
            $message .= ' Se cancelaron ' . $cancelledBookings . ' reservación(es) asociada(s).';
        }

        return redirect()->back()->with('success', $message);
    }

    private function cancelBookingsAndDeleteSlot(MaintenanceSlot $slot): array
    {
        return DB::transaction(function () use ($slot) {
            $slot->loadMissing(['bookings.ticket']);

            $cancelledBookings = 0;

            foreach ($slot->bookings as $booking) {
                $ticket = $booking->ticket;

                if ($ticket) {
                    $updates = [
                        'maintenance_slot_id' => null,
                        'maintenance_scheduled_at' => null,
                    ];

                    if ($ticket->tipo_problema === 'mantenimiento') {
                        $updates = array_merge($updates, [
                            'user_has_updates' => true,
                            'user_notified_at' => now(),
                            'user_notification_summary' => 'El horario reservado para tu mantenimiento fue cancelado por el equipo de TI.',
                        ]);
                    }

                    $ticket->forceFill($updates)->save();
                }

                $cancelledBookings++;
            }

            $slot->delete();

            return [
                'cancelled_bookings' => $cancelledBookings,
            ];
        });
    }

    public function computersIndex(): View
    {
        $profiles = ComputerProfile::with('ticket')->orderBy('identifier')->get();

        return view('admin.maintenance.computers', [
            'profiles' => $profiles,
            'componentOptions' => $this->getReplacementComponentOptions(),
        ]);
    }

    public function storeComputerProfile(Request $request): RedirectResponse
    {
        $validated = $this->validateComputerProfile($request);

        $profile = new ComputerProfile();
        $profile->fill(collect($validated)->except(['replacement_components', 'is_loaned'])->toArray());
        $profile->replacement_components = $validated['replacement_components'] ?? [];
        $profile->is_loaned = $request->boolean('is_loaned');

        if ($profile->is_loaned) {
            $profile->loaned_to_name = $validated['loaned_to_name'] ?? null;
            $profile->loaned_to_email = $validated['loaned_to_email'] ?? null;
        } else {
            $profile->loaned_to_name = null;
            $profile->loaned_to_email = null;
        }

        $profile->save();

        return redirect()
            ->route('admin.maintenance.computers.index')
            ->with('success', 'Ficha técnica registrada correctamente.');
    }

    public function showComputerProfile(ComputerProfile $profile): View
    {
        return view('admin.maintenance.computers.show', [
            'profile' => $profile,
            'componentOptions' => $this->getReplacementComponentOptions(),
        ]);
    }

    public function editComputerProfile(ComputerProfile $profile): View
    {
        return view('admin.maintenance.computers.edit', [
            'profile' => $profile,
            'componentOptions' => $this->getReplacementComponentOptions(),
        ]);
    }

    public function updateComputerProfile(Request $request, ComputerProfile $profile): RedirectResponse
    {
        $validated = $this->validateComputerProfile($request, $profile);

        $profile->fill(collect($validated)->except(['replacement_components', 'is_loaned'])->toArray());
        $profile->replacement_components = $validated['replacement_components'] ?? [];
        $profile->is_loaned = $request->boolean('is_loaned');

        if ($profile->is_loaned) {
            $profile->loaned_to_name = $validated['loaned_to_name'] ?? null;
            $profile->loaned_to_email = $validated['loaned_to_email'] ?? null;
        } else {
            $profile->loaned_to_name = null;
            $profile->loaned_to_email = null;
        }

        $profile->save();

        return redirect()
            ->route('admin.maintenance.computers.index')
            ->with('success', 'Ficha técnica actualizada correctamente.');
    }

    public function destroyComputerProfile(ComputerProfile $profile): RedirectResponse
    {
        $profile->delete();

        return redirect()
            ->route('admin.maintenance.computers.index')
            ->with('success', 'Ficha técnica eliminada correctamente.');
    }

    private function validateComputerProfile(Request $request, ?ComputerProfile $profile = null): array
    {
        $options = implode(',', array_keys($this->getReplacementComponentOptions()));

        $identifierRule = Rule::unique('computer_profiles', 'identifier');
        if ($profile) {
            $identifierRule = $identifierRule->ignore($profile->id);
        }

        $rules = [
            'identifier' => ['required', 'string', 'max:255', $identifierRule],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'disk_type' => ['nullable', 'string', 'max:255'],
            'ram_capacity' => ['nullable', 'string', 'max:255'],
            'battery_status' => ['nullable', 'in:functional,partially_functional,damaged'],
            'aesthetic_observations' => ['nullable', 'string'],
            'replacement_components' => ['nullable', 'array'],
            'replacement_components.*' => ['in:' . $options],
            'last_maintenance_at' => ['nullable', 'date'],
            'is_loaned' => ['nullable', 'boolean'],
            'loaned_to_name' => ['nullable', 'string', 'max:255', 'required_if:is_loaned,1'],
            'loaned_to_email' => ['nullable', 'email', 'max:255', 'required_if:is_loaned,1'],
        ];

        return $request->validate($rules);
    }

    private function getReplacementComponentOptions(): array
    {
        return [
            'disco_duro' => 'Disco duro',
            'ram' => 'RAM',
            'bateria' => 'Batería',
            'pantalla' => 'Pantalla',
            'conectores' => 'Conectores',
            'teclado' => 'Teclado',
            'mousepad' => 'Mousepad',
            'cargador' => 'Cargador',
        ];
    }
}
