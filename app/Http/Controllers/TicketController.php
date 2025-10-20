<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
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

        return view('tickets.create', compact('tipo'));
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
            'imagenes.*' => 'nullable|image|max:2048' // Máximo 2MB por imagen
        ]);

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
        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'nombre_solicitante' => auth()->user()->name,
            'correo_solicitante' => auth()->user()->email,
            'nombre_programa' => $request->nombre_programa,
            'descripcion_problema' => $request->descripcion_problema,
            'tipo_problema' => $request->tipo_problema,
            'imagenes' => $imagenes,
            'estado' => 'abierto',
            'is_read' => false,
            'notified_at' => now()
        ]);

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
        $request->validate([
            'estado' => 'required|in:abierto,en_proceso,cerrado',
            'prioridad' => 'nullable|in:baja,media,alta,critica',
            'observaciones' => 'nullable|string'
        ]);

        $data = $request->only(['estado', 'prioridad', 'observaciones']);

        // Si se cierra el ticket, agregar fecha de cierre
        if ($request->estado === 'cerrado' && $ticket->estado !== 'cerrado') {
            $data['fecha_cierre'] = now();
        }

        $ticket->update($data);

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
