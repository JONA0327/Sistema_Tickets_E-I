<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProblemaArchivo;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class ArchivoProblemasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = ProblemaArchivo::with(['ticket', 'archivadoPor']);

        // Filtros de búsqueda
        if ($request->filled('categoria')) {
            $query->categoria($request->categoria);
        }

        if ($request->filled('tipo_problema')) {
            $query->tipoProblema($request->tipo_problema);
        }

        if ($request->filled('frecuencia')) {
            $query->where('frecuencia', $request->frecuencia);
        }

        if ($request->filled('busqueda')) {
            $palabras = explode(' ', $request->busqueda);
            $query->buscarPalabras($palabras);
        }

        $problemas = $query->orderBy('fecha_archivo', 'desc')->paginate(15);

        // Obtener categorías y tipos para los filtros
        $categorias = ProblemaArchivo::distinct('categoria')->pluck('categoria');
        $tipos = ProblemaArchivo::distinct('tipo_problema')->pluck('tipo_problema');

        return view('archivo-problemas.index', compact('problemas', 'categorias', 'tipos'));
    }

    public function create($ticketId = null)
    {
        $ticket = null;
        if ($ticketId) {
            $ticket = Ticket::findOrFail($ticketId);
            
            // Verificar que el ticket esté cerrado
            if ($ticket->estado !== 'cerrado') {
                return redirect()->back()->with('error', 'Solo se pueden archivar tickets cerrados.');
            }
        }

        // Definir categorías predeterminadas
        $categorias = [
            'Hardware' => 'Hardware',
            'Software' => 'Software',
            'Red' => 'Red',
            'Sistema Operativo' => 'Sistema Operativo',
            'Aplicaciones' => 'Aplicaciones',
            'Seguridad' => 'Seguridad',
            'Base de Datos' => 'Base de Datos',
            'Impresoras' => 'Impresoras',
            'Telefonía' => 'Telefonía',
            'Otros' => 'Otros'
        ];

        $tiposProblema = [
            'Error de Sistema' => 'Error de Sistema',
            'Falla de Hardware' => 'Falla de Hardware',
            'Problema de Configuración' => 'Problema de Configuración',
            'Solicitud de Usuario' => 'Solicitud de Usuario',
            'Mantenimiento' => 'Mantenimiento',
            'Actualización' => 'Actualización',
            'Capacitación' => 'Capacitación',
            'Instalación' => 'Instalación'
        ];

        return view('archivo-problemas.create', compact('ticket', 'categorias', 'tiposProblema'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'categoria' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'descripcion_problema' => 'required|string',
            'solucion' => 'required|string',
            'tipo_problema' => 'required|string|max:255',
            'palabras_clave' => 'nullable|string',
            'frecuencia' => 'required|in:unico,ocasional,frecuente,critico',
            'notas_adicionales' => 'nullable|string'
        ], [
            'ticket_id.required' => 'El ticket es obligatorio.',
            'ticket_id.exists' => 'El ticket seleccionado no existe.',
            'categoria.required' => 'La categoría es obligatoria.',
            'titulo.required' => 'El título es obligatorio.',
            'descripcion_problema.required' => 'La descripción del problema es obligatoria.',
            'solucion.required' => 'La solución es obligatoria.',
            'tipo_problema.required' => 'El tipo de problema es obligatorio.',
            'frecuencia.required' => 'La frecuencia es obligatoria.',
            'frecuencia.in' => 'La frecuencia debe ser: único, ocasional, frecuente o crítico.'
        ]);

        // Verificar que el ticket esté cerrado
        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($ticket->estado !== 'cerrado') {
            return redirect()->back()->with('error', 'Solo se pueden archivar tickets cerrados.');
        }

        // Procesar palabras clave
        $palabrasClave = [];
        if ($request->filled('palabras_clave')) {
            $palabrasClave = array_map('trim', explode(',', $request->palabras_clave));
        }

        ProblemaArchivo::create([
            'ticket_id' => $request->ticket_id,
            'categoria' => $request->categoria,
            'titulo' => $request->titulo,
            'descripcion_problema' => $request->descripcion_problema,
            'solucion' => $request->solucion,
            'tipo_problema' => $request->tipo_problema,
            'palabras_clave' => $palabrasClave,
            'frecuencia' => $request->frecuencia,
            'notas_adicionales' => $request->notas_adicionales,
            'archivado_por' => Auth::id()
        ]);

        return redirect()->route('archivo-problemas.index')
                        ->with('success', 'Problema archivado correctamente.');
    }

    public function show($id)
    {
        $problema = ProblemaArchivo::with(['ticket', 'archivadoPor'])->findOrFail($id);
        return view('archivo-problemas.show', compact('problema'));
    }

    public function edit($id)
    {
        $problema = ProblemaArchivo::findOrFail($id);
        
        $categorias = [
            'Hardware' => 'Hardware',
            'Software' => 'Software',
            'Red' => 'Red',
            'Sistema Operativo' => 'Sistema Operativo',
            'Aplicaciones' => 'Aplicaciones',
            'Seguridad' => 'Seguridad',
            'Base de Datos' => 'Base de Datos',
            'Impresoras' => 'Impresoras',
            'Telefonía' => 'Telefonía',
            'Otros' => 'Otros'
        ];

        $tiposProblema = [
            'Error de Sistema' => 'Error de Sistema',
            'Falla de Hardware' => 'Falla de Hardware',
            'Problema de Configuración' => 'Problema de Configuración',
            'Solicitud de Usuario' => 'Solicitud de Usuario',
            'Mantenimiento' => 'Mantenimiento',
            'Actualización' => 'Actualización',
            'Capacitación' => 'Capacitación',
            'Instalación' => 'Instalación'
        ];

        return view('archivo-problemas.edit', compact('problema', 'categorias', 'tiposProblema'));
    }

    public function update(Request $request, $id)
    {
        $problema = ProblemaArchivo::findOrFail($id);

        $request->validate([
            'categoria' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'descripcion_problema' => 'required|string',
            'solucion' => 'required|string',
            'tipo_problema' => 'required|string|max:255',
            'palabras_clave' => 'nullable|string',
            'frecuencia' => 'required|in:unico,ocasional,frecuente,critico',
            'notas_adicionales' => 'nullable|string'
        ], [
            'categoria.required' => 'La categoría es obligatoria.',
            'titulo.required' => 'El título es obligatorio.',
            'descripcion_problema.required' => 'La descripción del problema es obligatoria.',
            'solucion.required' => 'La solución es obligatoria.',
            'tipo_problema.required' => 'El tipo de problema es obligatorio.',
            'frecuencia.required' => 'La frecuencia es obligatoria.',
            'frecuencia.in' => 'La frecuencia debe ser: único, ocasional, frecuente o crítico.'
        ]);

        // Procesar palabras clave
        $palabrasClave = [];
        if ($request->filled('palabras_clave')) {
            $palabrasClave = array_map('trim', explode(',', $request->palabras_clave));
        }

        $problema->update([
            'categoria' => $request->categoria,
            'titulo' => $request->titulo,
            'descripcion_problema' => $request->descripcion_problema,
            'solucion' => $request->solucion,
            'tipo_problema' => $request->tipo_problema,
            'palabras_clave' => $palabrasClave,
            'frecuencia' => $request->frecuencia,
            'notas_adicionales' => $request->notas_adicionales
        ]);

        return redirect()->route('archivo-problemas.show', $problema->id)
                        ->with('success', 'Problema actualizado correctamente.');
    }

    public function destroy($id)
    {
        $problema = ProblemaArchivo::findOrFail($id);
        $problema->delete();

        return redirect()->route('archivo-problemas.index')
                        ->with('success', 'Problema eliminado correctamente.');
    }

    // Método para obtener estadísticas
    public function estadisticas()
    {
        $stats = [
            'total_problemas' => ProblemaArchivo::count(),
            'por_categoria' => ProblemaArchivo::selectRaw('categoria, COUNT(*) as total')
                                             ->groupBy('categoria')
                                             ->orderBy('total', 'desc')
                                             ->get(),
            'por_frecuencia' => ProblemaArchivo::selectRaw('frecuencia, COUNT(*) as total')
                                              ->groupBy('frecuencia')
                                              ->orderBy('total', 'desc')
                                              ->get(),
            'por_tipo' => ProblemaArchivo::selectRaw('tipo_problema, COUNT(*) as total')
                                        ->groupBy('tipo_problema')
                                        ->orderBy('total', 'desc')
                                        ->get(),
            'recientes' => ProblemaArchivo::with(['ticket', 'archivadoPor'])
                                         ->orderBy('fecha_archivo', 'desc')
                                         ->take(5)
                                         ->get()
        ];

        return view('archivo-problemas.estadisticas', compact('stats'));
    }

    /**
     * Crear un ticket y archivarlo como problema resuelto en una sola operación
     * Solo disponible para administradores
     */
    public function createTicketAndArchive(Request $request)
    {
        // Verificar que el usuario sea administrador
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Validar los datos del formulario
        $request->validate([
            // Validaciones para el ticket
            'ticket_asunto' => 'required|string|max:255',
            'ticket_descripcion' => 'required|string',
            'ticket_categoria' => 'required|string|in:hardware,software,mantenimiento',
            'ticket_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            
            // Validaciones para el archivo
            'archivo_titulo' => 'required|string|max:255',
            'archivo_categoria' => 'required|string|in:hardware,software,red,impresora,sistema,otro',
            'archivo_tipo' => 'required|string|in:error,falla,configuracion,mantenimiento,actualizacion,otro',
            'archivo_frecuencia' => 'required|string|in:unico,ocasional,frecuente,critico',
            'archivo_solucion' => 'required|string',
            'solucion_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        try {
            // Crear el ticket primero
            $ticket = new Ticket();
            $ticket->user_id = Auth::id();
            $ticket->nombre_solicitante = Auth::user()->name;
            $ticket->correo_solicitante = Auth::user()->email;
            $ticket->descripcion_problema = $request->ticket_asunto . ': ' . $request->ticket_descripcion;
            $ticket->tipo_problema = $request->ticket_categoria;
            $ticket->estado = 'cerrado'; // Se marca como cerrado inmediatamente
            $ticket->fecha_cierre = now();
            $ticket->prioridad = 'media';

            // Procesar imágenes si se subieron
            $imagenes = [];
            if ($request->hasFile('ticket_imagenes')) {
                \Log::info('Procesando ' . count($request->file('ticket_imagenes')) . ' imágenes del ticket');
                foreach ($request->file('ticket_imagenes') as $index => $imagen) {
                    $imagenBase64 = base64_encode(file_get_contents($imagen->getRealPath()));
                    $imagenes[] = 'data:' . $imagen->getMimeType() . ';base64,' . $imagenBase64;
                    \Log::info('Imagen ' . ($index + 1) . ': ' . $imagen->getClientOriginalName());
                }
            }
            $ticket->imagenes = $imagenes;
            $ticket->save();

            // Crear el problema archivado
            $problema = new ProblemaArchivo();
            $problema->ticket_id = $ticket->id;
            $problema->categoria = $request->archivo_categoria;
            $problema->titulo = $request->archivo_titulo;
            $problema->descripcion_problema = $request->ticket_descripcion;
            $problema->solucion = $request->archivo_solucion;
            $problema->tipo_problema = $request->archivo_tipo;
            $problema->frecuencia = $request->archivo_frecuencia;
            $problema->fecha_archivo = now();
            $problema->archivado_por = Auth::id();

            // Generar palabras clave automáticamente a partir del título y descripción
            $textoCompleto = $request->archivo_titulo . ' ' . $request->ticket_descripcion . ' ' . $request->archivo_solucion;
            $palabras = str_word_count(strtolower($textoCompleto), 1, 'áéíóúñü');
            $palabrasFiltradasSet = collect($palabras)
                ->filter(function($palabra) {
                    // Filtrar palabras muy cortas y palabras comunes
                    $palabrasExcluir = ['para', 'con', 'por', 'una', 'que', 'del', 'las', 'los', 'como', 'pero', 'son', 'fue', 'esta', 'este'];
                    return strlen($palabra) > 3 && !in_array($palabra, $palabrasExcluir);
                })
                ->unique()
                ->take(10) // Máximo 10 palabras clave
                ->values()
                ->toArray();
            
            $problema->palabras_clave = $palabrasFiltradasSet;

            // Procesar imágenes de solución si se subieron
            $solucionImagenes = [];
            if ($request->hasFile('solucion_imagenes')) {
                \Log::info('Procesando ' . count($request->file('solucion_imagenes')) . ' imágenes de solución');
                foreach ($request->file('solucion_imagenes') as $index => $imagen) {
                    $imagenBase64 = base64_encode(file_get_contents($imagen->getRealPath()));
                    $solucionImagenes[] = [
                        'figura' => $index + 1,
                        'nombre' => $imagen->getClientOriginalName(),
                        'tipo' => $imagen->getMimeType(),
                        'data' => 'data:' . $imagen->getMimeType() . ';base64,' . $imagenBase64
                    ];
                    \Log::info('Figura ' . ($index + 1) . ': ' . $imagen->getClientOriginalName());
                }
            }
            $problema->solucion_imagenes = $solucionImagenes;
            
            $problema->save();

            return redirect()->route('archivo-problemas.index')
                           ->with('success', 'Ticket creado y problema archivado correctamente. Ticket #' . $ticket->codigo_seguridad);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el ticket y archivo: ' . $e->getMessage());
        }
    }
}
