<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\PrestamoInventario;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']); // Solo admins pueden crear/editar
    }

    /**
     * Mostrar lista de inventario con filtros
     */
    public function index(Request $request)
    {
        $query = Inventario::with(['createdBy', 'prestamosActivos.usuario']);

        // Filtros
        if ($request->filled('categoria')) {
            $query->categoria($request->categoria);
        }

        if ($request->filled('estado')) {
            $query->estado($request->estado);
        }

        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->where(function ($q) use ($busqueda) {
                $q->where('articulo', 'like', "%{$busqueda}%")
                  ->orWhere('modelo', 'like', "%{$busqueda}%")
                  ->orWhere('observaciones', 'like', "%{$busqueda}%");
            });
        }

        if ($request->filled('disponibles') && $request->disponibles == '1') {
            $query->disponibles();
        }

        if ($request->filled('funcionales') && $request->funcionales == '1') {
            $query->funcionales();
        }

        // Determinar si mostrar vista agrupada
        $vistaAgrupada = $request->get('agrupado', false);
        
        if ($vistaAgrupada) {
            // Vista agrupada: agrupar por categoría, artículo y modelo
            $inventarios = $query->get()
                                ->groupBy(function($item) {
                                    return $item->categoria . '|' . $item->articulo . '|' . $item->modelo;
                                })
                                ->map(function($grupo) {
                                    $representante = $grupo->first();
                                    $representante->unidades_grupo = $grupo;
                                    $representante->total_unidades_grupo = $grupo->count();
                                    $representante->unidades_disponibles_grupo = $grupo->where('cantidad_disponible', '>', 0)->count();
                                    return $representante;
                                })
                                ->values();
                                
            // Paginar manualmente la colección agrupada
            $page = request()->get('page', 1);
            $perPage = 20;
            $inventarios = new \Illuminate\Pagination\LengthAwarePaginator(
                $inventarios->forPage($page, $perPage),
                $inventarios->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            $inventarios = $query->orderBy('categoria')->orderBy('articulo')->paginate(20);
        }

        // Estadísticas para la vista
        $prestados = 0;
        try {
            $prestados = PrestamoInventario::activos()->count();
        } catch (\Exception $e) {
            // Si la tabla de préstamos no existe aún, usar 0
            $prestados = 0;
        }

        $stats = [
            'total' => Inventario::count(),
            'por_categoria' => Inventario::selectRaw('categoria, COUNT(*) as total')
                                       ->groupBy('categoria')
                                       ->orderBy('total', 'desc')
                                       ->get(),
            'funcionales' => Inventario::funcionales()->count(),
            'danados' => Inventario::where('estado', 'dañado')->count(),
            'prestados' => $prestados,
        ];

        return view('inventario.index', compact('inventarios', 'stats', 'vistaAgrupada'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(Request $request)
    {
        $categorias = Inventario::getCategorias();
        $estados = Inventario::getEstados();
        
        // Si se está creando una unidad similar a otra existente
        $similarItem = null;
        if ($request->filled('similar_to')) {
            $similarItem = Inventario::find($request->similar_to);
        }
        
        return view('inventario.create', compact('categorias', 'estados', 'similarItem'));
    }

    /**
     * Guardar nuevo artículo
     */
    public function store(Request $request)
    {
        // Validación base
        $rules = [
            'categoria' => 'required|in:mouse,discos_duros,memorias_ram,cargadores,baterias,computadoras,otros',
            'articulo' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'crear_como' => 'required|in:unidad_unica,multiples_unidades',
            // Campos específicos para computadoras
            'password_computadora' => 'nullable|string|max:255',
            'anos_uso' => 'nullable|integer|min:0|max:50',
        ];

        // Validación condicional según el tipo de creación
        if ($request->crear_como === 'multiples_unidades') {
            $rules['unidades'] = 'required|array|min:1|max:50';
            $rules['unidades.*.estado'] = 'required|in:nuevo,usado,dañado';
            $rules['unidades.*.observaciones'] = 'nullable|string';
            $rules['unidades.*.color'] = 'nullable|string|max:255';
        } else {
            $rules['cantidad'] = 'required|integer|min:1|max:50';
            $rules['estado'] = 'required|in:nuevo,usado,dañado';
            $rules['observaciones'] = 'nullable|string';
        }

        $request->validate($rules);

        try {
            // Preparar datos base compartidos
            $datosBase = [
                'categoria' => $request->categoria,
                'articulo' => $request->articulo,
                'modelo' => $request->modelo,
                'created_by' => Auth::id(),
            ];

            // Campos específicos para computadoras
            if ($request->categoria === 'computadoras') {
                $datosBase['password_computadora'] = $request->password_computadora;
                $datosBase['anos_uso'] = $request->anos_uso;
            }

            // Procesar imágenes una sola vez
            $imagenes = [];
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $imagen) {
                    $imagenBase64 = base64_encode(file_get_contents($imagen->getRealPath()));
                    $imagenes[] = $imagenBase64;
                }
            }
            $datosBase['imagenes'] = $imagenes;

            $codigosGenerados = [];

            if ($request->crear_como === 'multiples_unidades') {
                // Crear múltiples unidades individuales separadas
                $unidades = $request->unidades;
                foreach ($unidades as $index => $datosUnidad) {
                    $unidad = new Inventario($datosBase);
                    $unidad->cantidad = 1; // Cada unidad individual
                    $unidad->estado = $datosUnidad['estado'];
                    
                    // Combinar observaciones generales con las específicas de la unidad
                    $observacionesCompletas = [];
                    if (!empty($request->observaciones)) {
                        $observacionesCompletas[] = $request->observaciones;
                    }
                    if (!empty($datosUnidad['color'])) {
                        $observacionesCompletas[] = "Color: " . $datosUnidad['color'];
                    }
                    if (!empty($datosUnidad['observaciones'])) {
                        $observacionesCompletas[] = $datosUnidad['observaciones'];
                    }
                    
                    $unidad->observaciones = implode(' | ', $observacionesCompletas);
                    $unidad->save();
                    $codigosGenerados[] = $unidad->codigo_inventario;
                }
                
                $cantidad = count($unidades);
                $mensaje = "Se crearon {$cantidad} unidades individuales: " . implode(', ', $codigosGenerados);
            } else {
                // Crear como unidad única con la cantidad especificada
                $inventario = new Inventario($datosBase);
                $inventario->cantidad = $request->cantidad;
                $inventario->estado = $request->estado;
                $inventario->observaciones = $request->observaciones;
                $inventario->save();
                
                $mensaje = "Artículo creado como unidad única: {$inventario->codigo_inventario} (Cantidad: {$request->cantidad})";
            }

            return redirect()->route('inventario.index')
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al guardar el artículo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un artículo
     */
    public function show(Inventario $inventario)
    {
        $inventario->load(['createdBy', 'prestamosActivos.usuario']);
        $categorias = Inventario::getCategorias();
        $estados = Inventario::getEstados();
        $prestamosActivos = $inventario->prestamosActivos;

        return view('inventario.show', compact('inventario', 'categorias', 'estados', 'prestamosActivos'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Inventario $inventario)
    {
        $categorias = Inventario::getCategorias();
        $estados = Inventario::getEstados();
        
        return view('inventario.edit', compact('inventario', 'categorias', 'estados'));
    }

    /**
     * Actualizar artículo
     */
    public function update(Request $request, Inventario $inventario)
    {
        // Validación base
        $rules = [
            'categoria' => 'required|in:mouse,discos_duros,memorias_ram,cargadores,baterias,computadoras,otros',
            'articulo' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Campos específicos para computadoras
            'password_computadora' => 'nullable|string|max:255',
            'anos_uso' => 'nullable|integer|min:0|max:50',
        ];

        // Validación condicional según si es múltiple
        if ($request->filled('es_multiple')) {
            $rules['unidades'] = 'required|array|min:1';
            $rules['unidades.*.estado'] = 'required|in:nuevo,usado,dañado';
            $rules['unidades.*.observaciones'] = 'nullable|string';
            $rules['unidades.*.color'] = 'nullable|string|max:255';
        } else {
            $rules['cantidad'] = 'required|integer|min:1';
            $rules['estado'] = 'required|in:nuevo,usado,dañado';
        }
        
        $rules['observaciones'] = 'nullable|string';

        $request->validate($rules);

        try {
            // Actualizar campos base
            $inventario->categoria = $request->categoria;
            $inventario->articulo = $request->articulo;
            $inventario->modelo = $request->modelo;

            // Campos específicos para computadoras
            if ($request->categoria === 'computadoras') {
                $inventario->password_computadora = $request->password_computadora;
                $inventario->anos_uso = $request->anos_uso;
            } else {
                $inventario->password_computadora = null;
                $inventario->anos_uso = null;
            }

            if ($request->filled('es_multiple')) {
                // Actualizar múltiples unidades
                $unidades = $request->unidades;
                $cantidadTotal = count($unidades);
                
                $inventario->cantidad = $cantidadTotal;
                
                // Determinar el estado general (prioridad: dañado > usado > nuevo)
                $estados = array_column($unidades, 'estado');
                if (in_array('dañado', $estados)) {
                    $inventario->estado = 'dañado';
                } elseif (in_array('usado', $estados)) {
                    $inventario->estado = 'usado';
                } else {
                    $inventario->estado = 'nuevo';
                }
                
                // Reconstruir observaciones detalladas
                $observacionesCompletas = [];
                
                // Observaciones generales
                if (!empty($request->observaciones)) {
                    $observacionesCompletas[] = "GENERAL: " . $request->observaciones;
                }
                
                // Detalles de cada unidad
                $observacionesCompletas[] = "\n--- DETALLES POR UNIDAD ---";
                foreach ($unidades as $index => $datosUnidad) {
                    $detallesUnidad = [];
                    $detallesUnidad[] = "UNIDAD " . ($index + 1);
                    $detallesUnidad[] = "Estado: " . ucfirst($datosUnidad['estado']);
                    
                    if (!empty($datosUnidad['color'])) {
                        $detallesUnidad[] = "Color: " . $datosUnidad['color'];
                    }
                    
                    if (!empty($datosUnidad['observaciones'])) {
                        $detallesUnidad[] = "Notas: " . $datosUnidad['observaciones'];
                    }
                    
                    $observacionesCompletas[] = implode(' | ', $detallesUnidad);
                }
                
                $inventario->observaciones = implode("\n", $observacionesCompletas);
            } else {
                // Actualizar como unidad única
                $inventario->cantidad = $request->cantidad;
                $inventario->estado = $request->estado;
                $inventario->observaciones = $request->observaciones;
            }

            // Campos específicos para computadoras
            if ($request->categoria === 'computadoras') {
                $inventario->password_computadora = $request->password_computadora;
                $inventario->anos_uso = $request->anos_uso;
            } else {
                $inventario->password_computadora = null;
                $inventario->anos_uso = null;
            }

            // Procesar nuevas imágenes si se subieron
            if ($request->hasFile('imagenes')) {
                $imagenes = [];
                foreach ($request->file('imagenes') as $index => $imagen) {
                    $imagenBase64 = base64_encode(file_get_contents($imagen->getRealPath()));
                    $imagenes[] = [
                        'nombre' => $imagen->getClientOriginalName(),
                        'tipo' => $imagen->getMimeType(),
                        'data' => 'data:' . $imagen->getMimeType() . ';base64,' . $imagenBase64
                    ];
                }
                $inventario->imagenes = $imagenes;
            }
            
            $inventario->save();

            return redirect()->route('inventario.show', $inventario->id)
                           ->with('success', 'Artículo actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar el artículo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar artículo
     */
    public function destroy(Inventario $inventario)
    {

        // Verificar si tiene préstamos activos
        if ($inventario->prestamosActivos()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'No se puede eliminar este artículo porque tiene préstamos activos.');
        }

        $inventario->delete();

        return redirect()->route('inventario.index')
                       ->with('success', 'Artículo eliminado correctamente.');
    }

    /**
     * Eliminar una imagen específica del inventario
     */
    public function eliminarImagen(Request $request, Inventario $inventario)
    {
        $request->validate([
            'indice' => 'required|integer|min:0'
        ]);

        $imagenes = $inventario->imagenes ?? [];
        $indice = $request->indice;

        if (!isset($imagenes[$indice])) {
            return redirect()->back()->with('error', 'Imagen no encontrada.');
        }

        // Remover la imagen del array
        array_splice($imagenes, $indice, 1);

        // Actualizar el inventario
        $inventario->imagenes = empty($imagenes) ? null : $imagenes;
        $inventario->save();

        return redirect()->back()->with('success', 'Imagen eliminada correctamente.');
    }
}
