<?php

namespace App\Http\Controllers;

use App\Models\ComputerProfile;
use App\Models\MaintenanceBooking;
use App\Models\MaintenanceSlot;
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

        return view('tickets.create', compact('tipo'));
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
                Rule::requiredIf(fn () => $request->input('tipo_problema') === 'hardware'),
                'nullable',
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

        // Si se cierra el ticket, agregar fecha de cierre
        if ($validated['estado'] === 'cerrado' && $ticket->estado !== 'cerrado') {
            $data['fecha_cierre'] = now();
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

        $ticket->update($data);

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
