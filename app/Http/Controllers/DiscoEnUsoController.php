<?php

namespace App\Http\Controllers;

use App\Models\DiscoEnUso;
use App\Models\Inventario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscoEnUsoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Mostrar lista de discos en uso
     */
    public function index(Request $request)
    {
        $query = DiscoEnUso::with(['inventario', 'computadoraInventario', 'instaladoPor', 'retiradoPor']);

        // Filtros
        if ($request->filled('estado')) {
            if ($request->estado === 'activo') {
                $query->activos();
            } else {
                $query->retirados();
            }
        }

        if ($request->filled('computadora')) {
            $query->where('nombre_computadora', 'like', '%' . $request->computadora . '%');
        }

        $discosEnUso = $query->orderBy('fecha_instalacion', 'desc')->paginate(20);

        // Estadísticas
        $stats = [
            'activos' => DiscoEnUso::activos()->count(),
            'retirados' => DiscoEnUso::retirados()->count(),
            'este_mes' => DiscoEnUso::whereMonth('fecha_instalacion', now()->month)
                                   ->whereYear('fecha_instalacion', now()->year)
                                   ->count(),
            'total' => DiscoEnUso::count(),
        ];

        return view('discos-en-uso.index', compact('discosEnUso', 'stats'));
    }

    /**
     * Mostrar formulario para registrar disco en uso
     */
    public function create(Request $request)
    {
        $inventario_id = $request->get('inventario_id');
        $disco = null;
        
        if ($inventario_id) {
            $disco = Inventario::where('categoria', 'discos_duros')
                              ->where('id', $inventario_id)
                              ->first();
            
            if (!$disco) {
                return redirect()->route('inventario.index')
                               ->with('error', 'El artículo seleccionado no es un disco duro.');
            }

            if ($disco->discoEnUso) {
                return redirect()->route('discos-en-uso.show', $disco->discoEnUso)
                               ->with('error', 'Este disco ya está en uso.');
            }
        }

        // Obtener computadoras registradas en inventario
        $computadoras = Inventario::where('categoria', 'computadoras')->orderBy('articulo')->get();
        
        // Obtener discos duros disponibles
        $discosDisponibles = Inventario::where('categoria', 'discos_duros')
                                     ->whereDoesntHave('discoEnUso', function($query) {
                                         $query->where('esta_activo', true);
                                     })
                                     ->orderBy('articulo')
                                     ->get();

        return view('discos-en-uso.create', compact('disco', 'computadoras', 'discosDisponibles'));
    }

    /**
     * Registrar disco en uso
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventario_id' => 'required|exists:inventarios,id',
            'nombre_computadora' => 'required|string|max:255',
            'computadora_inventario_id' => 'nullable|exists:inventarios,id',
            'razon_uso' => 'required|string',
            'disco_reemplazado' => 'nullable|string|max:255',
            'detalles_reemplazo' => 'nullable|string',
            'fecha_instalacion' => 'required|date|before_or_equal:today',
            'observaciones' => 'nullable|string'
        ]);

        // Verificar que es un disco duro
        $disco = Inventario::findOrFail($request->inventario_id);
        if ($disco->categoria !== 'discos_duros') {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Solo se pueden registrar discos duros.');
        }

        // Verificar que no esté ya en uso
        if ($disco->discoEnUso) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Este disco ya está registrado como en uso.');
        }

        try {
            $discoEnUso = new DiscoEnUso();
            $discoEnUso->inventario_id = $request->inventario_id;
            $discoEnUso->nombre_computadora = $request->nombre_computadora;
            $discoEnUso->computadora_inventario_id = $request->computadora_inventario_id;
            $discoEnUso->razon_uso = $request->razon_uso;
            $discoEnUso->disco_reemplazado = $request->disco_reemplazado;
            $discoEnUso->detalles_reemplazo = $request->detalles_reemplazo;
            $discoEnUso->fecha_instalacion = $request->fecha_instalacion;
            $discoEnUso->observaciones = $request->observaciones;
            $discoEnUso->instalado_por = Auth::id();
            $discoEnUso->save();

            return redirect()->route('discos-en-uso.index')
                           ->with('success', 'Disco registrado en uso correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al registrar el disco: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles del disco en uso
     */
    public function show(DiscoEnUso $discoEnUso)
    {
        $discoEnUso->load(['inventario', 'computadoraInventario', 'instaladoPor', 'retiradoPor']);
        return view('discos-en-uso.show', compact('discoEnUso'));
    }

    /**
     * Retirar disco (marcar como no activo)
     */
    public function retirar(DiscoEnUso $discoEnUso)
    {
        return view('discos-en-uso.retirar', compact('discoEnUso'));
    }

    /**
     * Procesar retiro del disco
     */
    public function procesarRetiro(Request $request, DiscoEnUso $discoEnUso)
    {
        $request->validate([
            'fecha_retiro' => 'required|date',
            'observaciones_retiro' => 'required|string|max:1000',
            'disco_sustituto' => 'nullable|string|max:255'
        ]);

        try {
            $discoEnUso->esta_activo = false;
            $discoEnUso->fecha_retiro = $request->fecha_retiro;
            $discoEnUso->retirado_por = Auth::id();
            
            // Agregar observaciones de retiro
            if ($request->observaciones_retiro) {
                $observacionesRetiro = "--- RETIRO (" . now()->format('d/m/Y H:i') . ") ---\n";
                $observacionesRetiro .= "Retirado por: " . auth()->user()->name . "\n";
                $observacionesRetiro .= "Motivo: " . $request->observaciones_retiro;
                
                if ($request->disco_sustituto) {
                    $observacionesRetiro .= "\nDisco sustituto: " . $request->disco_sustituto;
                }
                
                $observacionesCompletas = $discoEnUso->observaciones_instalacion 
                    ? $discoEnUso->observaciones_instalacion . "\n\n" . $observacionesRetiro
                    : $observacionesRetiro;
                    
                $discoEnUso->observaciones_instalacion = $observacionesCompletas;
            }
            
            $discoEnUso->save();

            return redirect()->route('discos-en-uso.index')
                           ->with('success', 'Disco retirado correctamente. El disco ahora está disponible en el inventario.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al retirar el disco: ' . $e->getMessage());
        }
    }
}
