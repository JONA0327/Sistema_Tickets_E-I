<?php

namespace App\Http\Controllers;

use App\Models\ComputerProfile;
use App\Models\MaintenanceBooking;
use App\Models\MaintenanceSlot;
use App\Models\PrestamoInventario;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    /**
     * Mostrar formulario de ticket por tipo
     */
    public function create($tipo)
    {
        $tipos_validos = ['software', 'hardware', 'mantenimiento'];
        
        if (!in_array($tipo, $tipos_validos)) {
            abort(404);
        }

        $assignedComputerLoan = null;
        $assignedComputerProfile = null;
        $assignedPrinterLoan = null;

        if ($tipo === 'hardware' && auth()->check()) {
            $user = auth()->user();

            $assignedComputerLoan = PrestamoInventario::with('inventario')
                ->where('user_id', $user->id)
                ->where('estado_prestamo', 'activo')
                ->whereHas('inventario', function ($query) {
                    $query->where('categoria', 'computadoras');
                })
                ->latest('fecha_prestamo')
                ->first();

            if (!$assignedComputerLoan) {
                $assignedComputerProfile = ComputerProfile::where('is_loaned', true)
                    ->whereNotNull('loaned_to_email')
                    ->where('loaned_to_email', $user->email)
                    ->first();
            }

            $assignedPrinterLoan = PrestamoInventario::with('inventario')
                ->where('user_id', $user->id)
                ->where('estado_prestamo', 'activo')
                ->whereHas('inventario', function ($query) {
                    $query->where(function ($q) {
                        $q->where('articulo', 'like', '%impresora%')
                          ->orWhere('modelo', 'like', '%impresora%');
                    });
                })
                ->latest('fecha_prestamo')
                ->first();
        }

        return view('tickets.create', compact(
            'tipo',
            'assignedComputerLoan',
            'assignedComputerProfile',
            'assignedPrinterLoan'
        ));
    }

    /**
     * Guardar nuevo ticket
     */
    public function store(Request $request)
    {
        // Log de debug
        \Log::info('TicketController::store - Datos recibidos', [
            'tipo_problema' => $request->input('tipo_problema'),
            'maintenance_slot_id' => $request->input('maintenance_slot_id'),
            'all_input' => $request->all()
        ]);

        $validated = $request->validate([
            'nombre_programa' => [
                Rule::requiredIf(fn () => $request->input('tipo_problema') === 'hardware'),
                'nullable',
                'string',
                'max:255',
            ],
            'otro_programa_nombre' => 'nullable|string|max:255',
            'descripcion_problema' => [
                Rule::requiredIf(fn () => $request->input('tipo_problema') !== 'mantenimiento'),
                'string',
            ],
            'tipo_problema' => 'required|in:software,hardware,mantenimiento',
            'imagenes' => 'nullable|array|max:5',
            'imagenes.*' => 'nullable|image|max:2048',
            'maintenance_slot_id' => 'required_if:tipo_problema,mantenimiento|exists:maintenance_slots,id',
        ]);

        \Log::info('TicketController::store - Datos validados', $validated);

        // Manejar imágenes en base64
        $imagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $imageData = base64_encode(file_get_contents($imagen->getRealPath()));
                $mimeType = $imagen->getMimeType();
                $imagenes[] = [
                    'data' => $imageData,
                    'mime' => $mimeType,
                    'name' => $imagen->getClientOriginalName()
                ];
            }
        }

        $ticket = null;

        DB::transaction(function () use ($request, $imagenes, &$ticket, $validated) {
            // Determinar el nombre del programa
            $nombrePrograma = $validated['nombre_programa'] ?? null;
            if ($nombrePrograma === 'Otro' && !empty($validated['otro_programa_nombre'])) {
                $nombrePrograma = $validated['otro_programa_nombre'];
            }

            $ticketData = [
                'user_id' => auth()->id(),
                'nombre_solicitante' => auth()->user()->name,
                'correo_solicitante' => auth()->user()->email,
                'nombre_programa' => $nombrePrograma,
                'descripcion_problema' => $validated['descripcion_problema'] ?? '',
                'tipo_problema' => $validated['tipo_problema'],
                'imagenes' => $imagenes,
                'estado' => 'abierto',
                'is_read' => false,
                'notified_at' => now(),
            ];

            if ($validated['tipo_problema'] === 'mantenimiento') {
                /** @var MaintenanceSlot|null $slot */
                $slot = MaintenanceSlot::lockForUpdate()->find($validated['maintenance_slot_id'] ?? null);

                if (!$slot || !$slot->is_active) {
                    throw ValidationException::withMessages([
                        'maintenance_slot_id' => 'El horario seleccionado ya no está disponible.',
                    ]);
                }

                if ($slot->available_capacity <= 0) {
                    throw ValidationException::withMessages([
                        'maintenance_slot_id' => 'El horario seleccionado se encuentra lleno, por favor elige otro horario.',
                    ]);
                }

                $ticketData['maintenance_slot_id'] = $slot->id;
                $ticketData['maintenance_scheduled_at'] = $slot->start_date_time;

                $ticket = Ticket::create($ticketData);

                MaintenanceBooking::create([
                    'maintenance_slot_id' => $slot->id,
                    'ticket_id' => $ticket->id,
                    'additional_details' => $validated['descripcion_problema'] ?? '',
                ]);

                $slot->increment('booked_count');
                
                \Log::info('TicketController::store - Ticket de mantenimiento creado exitosamente', [
                    'ticket_id' => $ticket->id,
                    'folio' => $ticket->folio,
                    'slot_id' => $slot->id
                ]);
            } else {
                $ticket = Ticket::create($ticketData);
                
                \Log::info('TicketController::store - Ticket regular creado exitosamente', [
                    'ticket_id' => $ticket->id,
                    'folio' => $ticket->folio,
                    'tipo' => $ticket->tipo_problema
                ]);
            }
        });

        return redirect()->route('tickets.mis-tickets')->with('success',
            "¡Ticket creado exitosamente! Folio: {$ticket->folio}.");
    }

    /**
     * Ver todos los tickets (admin)
     */
    public function index()
    {
        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Ver detalles del ticket (admin)
     */
    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Actualizar ticket (admin)
     */
    public function update(Request $request, Ticket $ticket)
    {
        $rules = [
            'estado' => 'required|in:abierto,en_proceso,cerrado',
            'observaciones' => 'nullable|string',
        ];

        if ($ticket->tipo_problema === 'mantenimiento') {
            $rules = array_merge($rules, [
                'equipment_identifier' => 'nullable|string|max:255',
                'equipment_brand' => 'nullable|string|max:255',
                'equipment_model' => 'nullable|string|max:255',
                'equipment_password' => 'nullable|string|max:255',
                'disk_type' => 'nullable|string|max:255',
                'ram_capacity' => 'nullable|string|max:255',
                'battery_status' => 'nullable|in:functional,partially_functional,damaged',
                'aesthetic_observations' => 'nullable|string',
                'maintenance_report' => 'nullable|string',
                'closure_observations' => 'nullable|string',
                'replacement_components' => 'nullable|array',
                'replacement_components.*' => 'in:disco_duro,ram,bateria,pantalla,conectores,teclado,mousepad,cargador',
                'mark_as_loaned' => 'nullable|boolean',
                'imagenes_admin' => 'nullable|array',
                'imagenes_admin.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'removed_admin_images' => 'nullable|array',
                'removed_admin_images.*' => 'integer',
            ]);
        }

        if ($ticket->tipo_problema !== 'mantenimiento') {
            $rules['prioridad'] = 'nullable|in:baja,media,alta,critica';
        }

        $validated = $request->validate($rules);

        $data = collect($validated)->only(['estado', 'observaciones', 'prioridad'])->toArray();

        $originalEstado = $ticket->estado;
        $originalObservaciones = $ticket->observaciones;
        $originalMaintenanceReport = $ticket->maintenance_report;
        $originalClosureObservations = $ticket->closure_observations;

        // Si se cierra el ticket, agregar fecha de cierre
        if ($validated['estado'] === 'cerrado' && $ticket->estado !== 'cerrado') {
            $data['fecha_cierre'] = now();
        }

        if ($validated['estado'] !== 'cerrado') {
            $data['closed_by_user'] = false;
            $data['closed_by_user_at'] = null;
        } elseif ($originalEstado !== 'cerrado') {
            $data['closed_by_user'] = false;
            $data['closed_by_user_at'] = null;
        }

        $maintenanceData = [];

        if ($ticket->tipo_problema === 'mantenimiento') {
            $maintenanceFields = [
                'equipment_identifier',
                'equipment_brand',
                'equipment_model',
                'equipment_password',
                'disk_type',
                'ram_capacity',
                'battery_status',
                'aesthetic_observations',
                'maintenance_report',
                'closure_observations',
            ];

            foreach ($maintenanceFields as $field) {
                if (array_key_exists($field, $validated)) {
                    $maintenanceData[$field] = $validated[$field];
                }
            }

            $maintenanceData['replacement_components'] = $request->has('replacement_components')
                ? ($validated['replacement_components'] ?? [])
                : [];

            // Procesamiento de imágenes del administrador
            $existingImages = $ticket->imagenes_admin ?? [];
            
            // Remover imágenes marcadas para eliminación
            if ($request->has('removed_admin_images')) {
                $removedIndices = $request->input('removed_admin_images');
                foreach ($removedIndices as $index) {
                    unset($existingImages[$index]);
                }
                // Reindexar el array
                $existingImages = array_values($existingImages);
            }

            // Agregar nuevas imágenes
            if ($request->hasFile('imagenes_admin')) {
                foreach ($request->file('imagenes_admin') as $imagen) {
                    if ($imagen->isValid()) {
                        $imageData = base64_encode(file_get_contents($imagen->getPathname()));
                        $existingImages[] = $imageData;
                    }
                }
            }

            $maintenanceData['imagenes_admin'] = $existingImages;

            $data = array_merge($data, $maintenanceData);
        }

        $ticket->fill($data);
        $ticket->save();

        if ($ticket->tipo_problema === 'mantenimiento') {
            $profileData = [
                'identifier' => $ticket->equipment_identifier,
                'brand' => $ticket->equipment_brand,
                'model' => $ticket->equipment_model,
                'disk_type' => $ticket->disk_type,
                'ram_capacity' => $ticket->ram_capacity,
                'battery_status' => $ticket->battery_status,
                'aesthetic_observations' => $ticket->aesthetic_observations,
                'replacement_components' => $ticket->replacement_components,
                'last_ticket_id' => $ticket->id,
            ];

            if ($validated['estado'] === 'cerrado') {
                $profileData['last_maintenance_at'] = now();
            }

            $profile = $ticket->computerProfile;

            if (!$profile && $ticket->equipment_identifier) {
                $profile = ComputerProfile::firstOrNew(['identifier' => $ticket->equipment_identifier]);
            }

            if (!$profile) {
                $profile = new ComputerProfile();
            }

            $profile->fill($profileData);

            $markAsLoaned = $request->boolean('mark_as_loaned');
            $profile->is_loaned = $markAsLoaned;
            if ($markAsLoaned) {
                $profile->loaned_to_name = $ticket->nombre_solicitante;
                $profile->loaned_to_email = $ticket->correo_solicitante;
            } else {
                $profile->loaned_to_name = null;
                $profile->loaned_to_email = null;
            }

            $profile->save();

            $ticket->computer_profile_id = $profile->id;
            $ticket->save();
        }

        $userNotificationMessages = [];

        if ($originalEstado !== $ticket->estado) {
            $estadoLabel = ucfirst(str_replace('_', ' ', $ticket->estado));
            if ($ticket->estado === 'cerrado') {
                $userNotificationMessages[] = 'Tu ticket fue marcado como cerrado por el equipo de TI.';
            } else {
                $userNotificationMessages[] = "El estado del ticket cambió a \"{$estadoLabel}\".";
            }
        }

        if (!empty($ticket->observaciones) && $ticket->observaciones !== $originalObservaciones) {
            $userNotificationMessages[] = 'El administrador dejó un nuevo comentario en tu ticket.';
        }

        if ($ticket->tipo_problema === 'mantenimiento') {
            if (!empty($ticket->maintenance_report) && $ticket->maintenance_report !== $originalMaintenanceReport) {
                $userNotificationMessages[] = 'Se actualizó el reporte de mantenimiento del equipo.';
            }

            if (!empty($ticket->closure_observations) && $ticket->closure_observations !== $originalClosureObservations) {
                $userNotificationMessages[] = 'Se agregaron nuevas observaciones de cierre.';
            }
        }

        if (!empty($userNotificationMessages)) {
            $ticket->forceFill([
                'user_has_updates' => true,
                'user_notified_at' => now(),
                'user_notification_summary' => implode(' ', $userNotificationMessages),
            ])->save();
        }

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket actualizado exitosamente');
    }

    /**
     * Ver mis tickets (usuario autenticado)
     */
    public function misTickets(Request $request)
    {
        $tickets = Ticket::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        $notificationsCount = $tickets->where('user_has_updates', true)->count();
        $supportEmail = config('support.contact_email');
        $supportTeamsUrl = config('support.teams_url');

        return view('tickets.mis-tickets', compact(
            'tickets',
            'notificationsCount',
            'supportEmail',
            'supportTeamsUrl'
        ));
    }

    /**
     * Marcar una actualización de ticket como revisada por el usuario
     */
    public function acknowledgeUpdate(Request $request, Ticket $ticket)
    {
        abort_if($ticket->user_id !== auth()->id(), 403);

        $ticket->forceFill([
            'user_has_updates' => false,
            'user_last_read_at' => now(),
        ])->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Actualización marcada como revisada.',
            ]);
        }

        return redirect()->route('tickets.mis-tickets')->with('success', 'Actualización marcada como revisada.');
    }

    /**
     * Marcar todas las actualizaciones como revisadas por el usuario
     */
    public function acknowledgeAllUpdates(Request $request)
    {
        $updated = Ticket::where('user_id', auth()->id())
            ->where('user_has_updates', true)
            ->update([
                'user_has_updates' => false,
                'user_last_read_at' => now(),
            ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todas las actualizaciones fueron marcadas como revisadas.',
                'updated' => $updated,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', $updated > 0
                ? 'Todas las notificaciones fueron marcadas como revisadas.'
                : 'No tienes notificaciones pendientes.');
    }

    /**
     * Eliminar ticket
     */
    public function destroy(Ticket $ticket)
    {
        $user = auth()->user();
        $folio = $ticket->folio;
        $solicitante = $ticket->nombre_solicitante;
        $isAdmin = $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false;

        // Liberar slot de mantenimiento si es un ticket de mantenimiento
        if ($ticket->tipo_problema === 'mantenimiento' && $ticket->maintenance_slot_id) {
            $this->releaseMaintenanceSlot($ticket);
        }

        if ($isAdmin) {
            $ticket->delete();

            return redirect()->back()->with(
                'success',
                "Ticket {$folio} de {$solicitante} eliminado exitosamente desde el panel administrativo." .
                ($ticket->tipo_problema === 'mantenimiento' ? ' El horario de mantenimiento ha sido liberado.' : '')
            );
        }

        if (!$user || $ticket->user_id !== $user->id) {
            return redirect()->back()->with('error', 'No tienes permiso para cancelar este ticket.');
        }

        if ($ticket->estado === 'cerrado' && $ticket->closed_by_user) {
            return redirect()->back()->with(
                'info',
                "El ticket {$folio} ya había sido cancelado anteriormente."
            );
        }

        $ticket->forceFill([
            'estado' => 'cerrado',
            'fecha_cierre' => now(),
            'closed_by_user' => true,
            'closed_by_user_at' => now(),
            'is_read' => false,
            'notified_at' => now(),
        ])->save();

        $message = "Ticket {$folio} cancelado exitosamente. El equipo de TI ha sido notificado.";
        if ($ticket->tipo_problema === 'mantenimiento') {
            $message .= " El horario de mantenimiento ha sido liberado para que otros usuarios puedan agendarlo.";
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Liberar slot de mantenimiento cuando se cancela un ticket
     */
    private function releaseMaintenanceSlot(Ticket $ticket)
    {
        try {
            // Buscar la reserva de mantenimiento
            $booking = MaintenanceBooking::where('ticket_id', $ticket->id)->first();
            
            if ($booking) {
                $slot = $booking->slot;
                
                // Decrementar el contador de reservas
                if ($slot && $slot->booked_count > 0) {
                    $slot->decrement('booked_count');
                }
                
                // Eliminar la reserva
                $booking->delete();
                
                \Log::info('MaintenanceSlot liberado', [
                    'ticket_id' => $ticket->id,
                    'slot_id' => $slot->id ?? 'no encontrado',
                    'fecha' => $slot->date ?? 'no disponible',
                    'hora' => $slot->start_time ?? 'no disponible'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al liberar MaintenanceSlot', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enviar email de confirmación
     */
    private function enviarEmailConfirmacion(Ticket $ticket)
    {
        try {
            // Solo enviar email si está configurado
            if (config('mail.default') && config('mail.mailers.smtp.host')) {
                Mail::send('emails.ticket-confirmacion', ['ticket' => $ticket], function($message) use ($ticket) {
                    $message->to($ticket->correo_solicitante, $ticket->nombre_solicitante)
                            ->subject("Ticket Creado - Folio: {$ticket->folio}");
                });
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando email: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar fecha de mantenimiento (Solo administrador)
     */
    public function changeMaintenanceDate(Request $request, Ticket $ticket)
    {
        // Verificar que el usuario sea administrador
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Verificar que sea un ticket de mantenimiento
        if ($ticket->tipo_problema !== 'mantenimiento') {
            return redirect()->back()->with('error', 'Solo se puede cambiar la fecha de tickets de mantenimiento.');
        }

        // Validar la nueva fecha
        $request->validate([
            'new_maintenance_slot_id' => 'required|exists:maintenance_slots,id',
        ], [
            'new_maintenance_slot_id.required' => 'Debes seleccionar un nuevo horario.',
            'new_maintenance_slot_id.exists' => 'El horario seleccionado no es válido.',
        ]);

        $newSlot = MaintenanceSlot::find($request->new_maintenance_slot_id);
        
        // Verificar que el nuevo slot tenga capacidad disponible
        if ($newSlot->available_capacity <= 0) {
            return redirect()->back()->with('error', 'El horario seleccionado no tiene capacidad disponible.');
        }

        try {
            DB::transaction(function () use ($ticket, $newSlot) {
                // Liberar el slot actual si existe
                if ($ticket->maintenance_slot_id) {
                    $this->releaseMaintenanceSlot($ticket);
                }

                // Asignar el nuevo slot
                $booking = MaintenanceBooking::create([
                    'maintenance_slot_id' => $newSlot->id,
                    'ticket_id' => $ticket->id,
                    'additional_details' => 'Fecha cambiada por administrador',
                ]);

                // Incrementar el contador de reservas del nuevo slot
                $newSlot->increment('booked_count');

                // Actualizar el ticket
                $ticket->update([
                    'maintenance_slot_id' => $newSlot->id,
                    'is_read' => false,
                    'user_has_updates' => true,
                    'notified_at' => now(),
                ]);

                \Log::info('Fecha de mantenimiento cambiada por administrador', [
                    'ticket_id' => $ticket->id,
                    'folio' => $ticket->folio,
                    'new_slot_id' => $newSlot->id,
                    'new_date' => $newSlot->date,
                    'new_time' => $newSlot->start_time,
                    'admin_id' => auth()->id(),
                ]);
            });

            return redirect()->back()->with('success', 
                "La fecha del mantenimiento ha sido cambiada exitosamente. El usuario será notificado del cambio."
            );

        } catch (\Exception $e) {
            \Log::error('Error al cambiar fecha de mantenimiento', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Hubo un error al cambiar la fecha del mantenimiento.');
        }
    }

    /**
     * Obtener slots de mantenimiento disponibles (para AJAX)
     */
    public function getAvailableMaintenanceSlots(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $slots = MaintenanceSlot::active()
            ->where('date', '>=', now()->toDateString())
            ->where('capacity', '>', DB::raw('booked_count'))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get(['id', 'date', 'start_time', 'end_time', 'capacity', 'booked_count'])
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'date' => $slot->date->format('Y-m-d'),
                    'date_formatted' => $slot->date->format('d/m/Y'),
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'available_capacity' => $slot->available_capacity,
                    'display_text' => $slot->date->format('d/m/Y') . ' de ' . 
                                    $slot->start_time . ' a ' . $slot->end_time . 
                                    ' (' . $slot->available_capacity . ' disponibles)'
                ];
            });

        return response()->json($slots);
    }
}
