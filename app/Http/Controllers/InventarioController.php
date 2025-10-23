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

        // Determinar si mostrar vista agrupada - activar por defecto si hay unidades similares
        $hasMultipleUnits = Inventario::selectRaw('categoria, articulo, modelo, COUNT(*) as count')
                                     ->groupBy('categoria', 'articulo', 'modelo')
                                     ->having('count', '>', 1)
                                     ->exists();
        
        $vistaAgrupada = $request->get('agrupado', $hasMultipleUnits);
        
        if ($vistaAgrupada) {
            // Vista agrupada: agrupar por categoría, artículo y modelo
            $inventarios = $query->get()
                                ->groupBy(function($item) {
                                    return $item->categoria . '|' . $item->articulo . '|' . $item->modelo;
                                })
                                ->map(function($grupo) {
                                    $funcionales = 0;
                                    $danadas = 0;
                                    $disponibles = 0;
                                    
                                    foreach ($grupo as $item) {
                                        if (in_array($item->estado, ['nuevo', 'usado'])) {
                                            $funcionales++;
                                        }
                                        if ($item->estado === 'dañado') {
                                            $danadas++;
                                        }
                                        if ($item->esta_disponible) {
                                            $disponibles++;
                                        }
                                    }
                                    
                                    return [
                                        'grupo' => $grupo->first()->articulo . ' ' . $grupo->first()->modelo,
                                        'categoria' => $grupo->first()->categoria_formateada,
                                        'total' => $grupo->count(),
                                        'disponibles' => $disponibles,
                                        'funcionales' => $funcionales,
                                        'danadas' => $danadas,
                                        'inventarios' => $grupo,
                                        'representante' => $grupo->first()
                                    ];
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
            'color_primario' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_secundario' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
            $rules['unidades.*.color_primario'] = 'nullable|regex:/^#[0-9A-Fa-f]{6}$/';
            $rules['unidades.*.color_secundario'] = 'nullable|regex:/^#[0-9A-Fa-f]{6}$/';
        } else {
            $rules['cantidad'] = 'required|integer|min:1|max:50';
            $rules['estado'] = 'required|in:nuevo,usado,dañado';
            $rules['observaciones'] = 'nullable|string';
        }

        $request->validate($rules);

        try {
            $colorPrimarioBase = $request->input('color_primario') ?: null;
            $colorSecundarioBase = $request->input('color_secundario') ?: null;

            // Preparar datos base compartidos
            $datosBase = [
                'categoria' => $request->categoria,
                'articulo' => $request->articulo,
                'modelo' => $request->modelo,
                'color_primario' => $colorPrimarioBase,
                'color_secundario' => $colorSecundarioBase,
                'created_by' => Auth::id(),
            ];

            // Campos específicos para computadoras
            if ($request->categoria === 'computadoras') {
                $datosBase['password_computadora'] = $request->password_computadora;
                $datosBase['anos_uso'] = $request->anos_uso;
            }

            // Campos específicos para discos duros con información
            if ($request->categoria === 'discos_duros') {
                $datosBase['tiene_informacion'] = $request->boolean('tiene_informacion', false);
                $datosBase['informacion_contenido'] = $request->informacion_contenido;
                $datosBase['nivel_confidencialidad'] = $request->nivel_confidencialidad;
                $datosBase['bloqueado_prestamo'] = $request->boolean('bloqueado_prestamo', false);
                $datosBase['razon_bloqueo'] = $request->razon_bloqueo;
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

                    $colorPrimarioUnidad = $datosUnidad['color_primario'] ?? null;
                    $colorSecundarioUnidad = $datosUnidad['color_secundario'] ?? null;

                    $unidad->color_primario = $colorPrimarioUnidad ?: $colorPrimarioBase;
                    $unidad->color_secundario = $colorSecundarioUnidad ?: $colorSecundarioBase;

                    // Combinar observaciones generales con las específicas de la unidad
                    $observacionesCompletas = [];
                    if (!empty($request->observaciones)) {
                        $observacionesCompletas[] = $request->observaciones;
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
                $inventario->color_primario = $colorPrimarioBase;
                $inventario->color_secundario = $colorSecundarioBase;
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
        
        // Cargar información de discos en uso si es un disco duro
        $discoEnUso = null;
        if ($inventario->categoria === 'discos_duros') {
            $discoEnUso = $inventario->discoEnUso()->with('usuario')->first();
        }

        return view('inventario.show', compact('inventario', 'categorias', 'estados', 'prestamosActivos', 'discoEnUso'));
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
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'color_primario' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_secundario' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            // Campos específicos para computadoras
            'password_computadora' => 'nullable|string|max:255',
            'anos_uso' => 'nullable|integer|min:0|max:50',
            // Campos para discos con información
            'tiene_informacion' => 'nullable|boolean',
            'informacion_contenido' => 'nullable|string',
            'nivel_confidencialidad' => 'nullable|in:bajo,medio,alto,critico',
            'bloqueado_prestamo' => 'nullable|boolean',
            'razon_bloqueo' => 'nullable|string'
        ];

        // Validación condicional - más flexible para actualizaciones parciales
        if ($request->filled('es_multiple')) {
            // Si se envían unidades, validarlas
            if ($request->has('unidades') && is_array($request->unidades)) {
                $rules['unidades'] = 'array|min:1';
                $rules['unidades.*.estado'] = 'required|in:nuevo,usado,dañado';
                $rules['unidades.*.observaciones'] = 'nullable|string';
                $rules['unidades.*.color_primario'] = 'nullable|regex:/^#[0-9A-Fa-f]{6}$/';
                $rules['unidades.*.color_secundario'] = 'nullable|regex:/^#[0-9A-Fa-f]{6}$/';
            }
        } else {
            // Solo requerir estos campos si no es múltiple
            $rules['cantidad'] = 'required|integer|min:1';
            $rules['estado'] = 'required|in:nuevo,usado,dañado';
        }
        
        $rules['observaciones'] = 'nullable|string';

        $request->validate($rules);

        try {
            // Actualizar campos base SIEMPRE
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

            // Campos específicos para discos duros con información
            if ($request->categoria === 'discos_duros') {
                $inventario->tiene_informacion = $request->boolean('tiene_informacion', false);
                $inventario->informacion_contenido = $request->informacion_contenido;
                $inventario->nivel_confidencialidad = $request->nivel_confidencialidad;
                $inventario->bloqueado_prestamo = $request->boolean('bloqueado_prestamo', false);
                $inventario->razon_bloqueo = $request->razon_bloqueo;
            } else {
                // Limpiar campos si no es disco duro
                $inventario->tiene_informacion = false;
                $inventario->informacion_contenido = null;
                $inventario->nivel_confidencialidad = null;
                $inventario->bloqueado_prestamo = false;
                $inventario->razon_bloqueo = null;
            }

            // Determinar si es edición múltiple o individual
            $esMultiple = $request->filled('es_multiple');
            
            if ($esMultiple && $request->has('unidades') && is_array($request->unidades) && count($request->unidades) > 0) {
                // Actualización de sistema múltiple con datos de unidades
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

                    if (!empty($datosUnidad['color_primario'])) {
                        $detallesUnidad[] = "Color primario: " . strtoupper($datosUnidad['color_primario']);
                    }

                    if (!empty($datosUnidad['color_secundario'])) {
                        $detallesUnidad[] = "Color secundario: " . strtoupper($datosUnidad['color_secundario']);
                    }

                    if (!empty($datosUnidad['observaciones'])) {
                        $detallesUnidad[] = "Notas: " . $datosUnidad['observaciones'];
                    }
                    
                    $observacionesCompletas[] = implode(' | ', $detallesUnidad);
                }
                
                $inventario->observaciones = implode("\n", $observacionesCompletas);
            } else {
                // Actualización simple - SIEMPRE actualizar estos campos cuando NO es múltiple
                if ($request->has('cantidad')) {
                    $inventario->cantidad = $request->cantidad;
                }
                if ($request->has('estado')) {
                    $inventario->estado = $request->estado;
                }
                if ($request->has('observaciones')) {
                    $inventario->observaciones = $request->observaciones;
                }
            }

            $inventario->color_primario = $request->input('color_primario') ?: null;
            $inventario->color_secundario = $request->input('color_secundario') ?: null;

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
