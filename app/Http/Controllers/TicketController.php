<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceEquipmentRecord;
use App\Models\MaintenanceTimeSlot;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        $slots = [];

        if ($tipo === 'mantenimiento') {
            $slots = MaintenanceTimeSlot::withCount('tickets')
                ->where('is_open', true)
                ->orderBy('date')
                ->orderBy('start_time')
                ->get()
                ->map(function ($slot) {
                    return [
                        'id' => $slot->id,
                        'date' => $slot->date->format('Y-m-d'),
                        'start_time' => $slot->start_time ? $slot->start_time->format('H:i') : null,
                        'end_time' => $slot->end_time ? $slot->end_time->format('H:i') : null,
                        'capacity' => $slot->capacity,
                        'booked' => $slot->tickets_count,
                        'available' => max(0, $slot->capacity - $slot->tickets_count),
                        'notes' => $slot->notes,
                    ];
                });
        }

        return view('tickets.create', [
            'tipo' => $tipo,
            'slots' => $slots,
        ]);
    }

    /**
     * Guardar nuevo ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_programa' => 'nullable|string|max:255',
            'descripcion_problema' => 'required|string',
            'tipo_problema' => 'required|in:software,hardware,mantenimiento',
            'imagenes.*' => 'nullable|image|max:2048', // Máximo 2MB por imagen
            'maintenance_time_slot_id' => 'required_if:tipo_problema,mantenimiento|exists:maintenance_time_slots,id',
            'maintenance_details' => 'nullable|string',
        ]);

        $slot = null;
        if ($request->tipo_problema === 'mantenimiento') {
            $slot = MaintenanceTimeSlot::withCount('tickets')->findOrFail($request->maintenance_time_slot_id);

            if (!$slot->is_open || $slot->available_capacity <= 0) {
                return redirect()->back()->withErrors([
                    'maintenance_time_slot_id' => 'El horario seleccionado ya no se encuentra disponible. Por favor selecciona otro.',
                ])->withInput();
            }
        }

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

        // Crear ticket asociado al usuario autenticado
        $ticketData = [
            'user_id' => auth()->id(),
            'nombre_solicitante' => auth()->user()->name,
            'correo_solicitante' => auth()->user()->email,
            'nombre_programa' => $request->nombre_programa,
            'descripcion_problema' => $request->descripcion_problema,
            'tipo_problema' => $request->tipo_problema,
            'imagenes' => $imagenes,
            'estado' => 'abierto',
        ];

        if ($slot) {
            $ticketData = array_merge($ticketData, [
                'maintenance_time_slot_id' => $slot->id,
                'maintenance_date' => $slot->date,
                'maintenance_time' => $slot->start_time,
                'maintenance_details' => $request->maintenance_details,
            ]);
        }

        $ticket = Ticket::create($ticketData);

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
        $maintenanceSlots = collect();

        if ($ticket->tipo_problema === 'mantenimiento') {
            $maintenanceSlots = MaintenanceTimeSlot::withCount('tickets')
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();
        }

        return view('admin.tickets.show', [
            'ticket' => $ticket,
            'maintenanceSlots' => $maintenanceSlots,
        ]);
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

        if ($ticket->tipo_problema !== 'mantenimiento') {
            $rules['prioridad'] = 'nullable|in:baja,media,alta,critica';
        }

        $rules = array_merge($rules, [
            'equipo_marca' => 'nullable|string|max:255',
            'equipo_modelo' => 'nullable|string|max:255',
            'equipo_tipo_disco' => 'nullable|string|max:255',
            'equipo_ram_capacidad' => 'nullable|string|max:255',
            'equipo_observaciones_esteticas' => 'nullable|string',
            'equipo_bateria_estado' => 'nullable|in:funcional,parcialmente_funcional,danada',
            'maintenance_details' => 'nullable|string',
            'maintenance_cierre_observaciones' => 'nullable|string',
            'maintenance_reporte' => 'nullable|string',
            'maintenance_componentes_reemplazo' => 'nullable|array',
            'maintenance_componentes_reemplazo.*' => 'in:disco_duro,ram,bateria,pantalla,conectores,teclado,mousepad,cargador',
            'maintenance_time_slot_id' => 'nullable|exists:maintenance_time_slots,id',
        ]);

        $validated = $request->validate($rules);

        $data = [
            'estado' => $validated['estado'],
            'observaciones' => $validated['observaciones'] ?? null,
        ];

        if ($ticket->tipo_problema !== 'mantenimiento') {
            $data['prioridad'] = $validated['prioridad'] ?? null;
        }

        if ($ticket->tipo_problema === 'mantenimiento') {
            $data = array_merge($data, [
                'equipo_marca' => $validated['equipo_marca'] ?? null,
                'equipo_modelo' => $validated['equipo_modelo'] ?? null,
                'equipo_tipo_disco' => $validated['equipo_tipo_disco'] ?? null,
                'equipo_ram_capacidad' => $validated['equipo_ram_capacidad'] ?? null,
                'equipo_observaciones_esteticas' => $validated['equipo_observaciones_esteticas'] ?? null,
                'equipo_bateria_estado' => $validated['equipo_bateria_estado'] ?? null,
                'maintenance_details' => $validated['maintenance_details'] ?? $ticket->maintenance_details,
                'maintenance_reporte' => $validated['maintenance_reporte'] ?? $ticket->maintenance_reporte,
                'maintenance_componentes_reemplazo' => $validated['maintenance_componentes_reemplazo'] ?? $ticket->maintenance_componentes_reemplazo,
            ]);

            if (!empty($validated['maintenance_time_slot_id'])) {
                $slot = MaintenanceTimeSlot::withCount('tickets')->find($validated['maintenance_time_slot_id']);
                if ($slot) {
                    $available = max(0, $slot->capacity - $slot->tickets_count);
                    if ($ticket->maintenance_time_slot_id === $slot->id) {
                        $available += 1; // liberar el espacio del propio ticket
                    }

                    if (!$slot->is_open && $ticket->maintenance_time_slot_id !== $slot->id) {
                        return redirect()->back()->withErrors([
                            'maintenance_time_slot_id' => 'El horario seleccionado está cerrado para nuevas asignaciones.',
                        ])->withInput();
                    }

                    if ($available <= 0) {
                        return redirect()->back()->withErrors([
                            'maintenance_time_slot_id' => 'El horario seleccionado ya alcanzó su capacidad máxima.',
                        ])->withInput();
                    }

                    $data['maintenance_time_slot_id'] = $slot->id;
                    $data['maintenance_date'] = $slot->date;
                    $data['maintenance_time'] = $slot->start_time;
                }
            }

            if ($validated['estado'] === 'cerrado') {
                $data['maintenance_cierre_observaciones'] = $validated['maintenance_cierre_observaciones'] ?? null;
            }
        }

        // Si se cierra el ticket, agregar fecha de cierre
        if ($request->estado === 'cerrado' && $ticket->estado !== 'cerrado') {
            $data['fecha_cierre'] = now();
        }

        $ticket->update($data);
        $ticket->refresh();

        if ($ticket->tipo_problema === 'mantenimiento' && $ticket->estado === 'cerrado') {
            $programado = null;

            if ($ticket->maintenance_date) {
                $timeString = $ticket->maintenance_time instanceof Carbon
                    ? $ticket->maintenance_time->format('H:i')
                    : $ticket->maintenance_time;

                $programado = $timeString
                    ? Carbon::parse($ticket->maintenance_date->format('Y-m-d') . ' ' . $timeString)
                    : Carbon::parse($ticket->maintenance_date->format('Y-m-d'));
            }

            $record = MaintenanceEquipmentRecord::firstOrNew(['ticket_id' => $ticket->id]);
            $record->fill([
                'user_id' => $ticket->user_id,
                'usuario_nombre' => $ticket->nombre_solicitante,
                'usuario_correo' => $ticket->correo_solicitante,
                'equipo_marca' => $ticket->equipo_marca,
                'equipo_modelo' => $ticket->equipo_modelo,
                'equipo_tipo_disco' => $ticket->equipo_tipo_disco,
                'equipo_ram_capacidad' => $ticket->equipo_ram_capacidad,
                'equipo_observaciones_esteticas' => $ticket->equipo_observaciones_esteticas,
                'equipo_bateria_estado' => $ticket->equipo_bateria_estado,
                'maintenance_cierre_observaciones' => $ticket->maintenance_cierre_observaciones,
                'maintenance_reporte' => $ticket->maintenance_reporte,
                'maintenance_componentes_reemplazo' => $ticket->maintenance_componentes_reemplazo,
                'mantenimiento_programado' => $programado,
            ]);

            if (!$record->exists) {
                $record->prestado = false;
                $record->prestado_a_nombre = null;
                $record->prestado_a_correo = null;
            }

            $record->save();
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

        return view('tickets.mis-tickets', compact('tickets'));
    }

    public function maintenanceSlots(): JsonResponse
    {
        $slots = MaintenanceTimeSlot::withCount('tickets')
            ->where('is_open', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'date' => $slot->date->format('Y-m-d'),
                    'start_time' => $slot->start_time ? $slot->start_time->format('H:i') : null,
                    'end_time' => $slot->end_time ? $slot->end_time->format('H:i') : null,
                    'capacity' => $slot->capacity,
                    'booked' => $slot->tickets_count,
                    'available' => max(0, $slot->capacity - $slot->tickets_count),
                    'notes' => $slot->notes,
                ];
            });

        return response()->json($slots);
    }

    /**
     * Eliminar ticket
     */
    public function destroy(Ticket $ticket)
    {
        $folio = $ticket->folio;
        $solicitante = $ticket->nombre_solicitante;
        
        // Detectar desde dónde viene la eliminación
        $referrer = request()->headers->get('referer');
        $isAdmin = str_contains($referrer, 'admin');
        
        // Si no es admin, verificar que el usuario sea el propietario del ticket
        if (!$isAdmin && $ticket->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este ticket.');
        }
        
        $ticket->delete();
        
        // Mensaje personalizado según el origen
        $message = "Ticket {$folio}";
        
        if ($isAdmin) {
            $message .= " de {$solicitante} eliminado exitosamente desde el panel administrativo.";
        } else {
            $message .= " eliminado exitosamente.";
        }
        
        return redirect()->back()->with('success', $message);
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
}
