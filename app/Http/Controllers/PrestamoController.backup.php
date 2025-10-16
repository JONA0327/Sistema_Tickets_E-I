<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\Inventario;
use App\Models\PrestamoInventario;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Solo admins pueden gestionar préstamos
    }

    /**
     * Mostrar lista de préstamos
     */
    public function index(Request $request)
    {
        $query = PrestamoInventario::with(['inventario', 'usuario', 'prestadoPor', 'recibidoPor']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado_prestamo', $request->estado);
        }

        if ($request->filled('usuario_id')) {
            $query->where('user_id', $request->usuario_id);
        }

        if ($request->filled('categoria')) {
            $query->whereHas('inventario', function ($q) use ($request) {
                $q->where('categoria', $request->categoria);
            });
        }

        $prestamos = $query->orderBy('fecha_prestamo', 'desc')->paginate(20);

        // Datos para filtros
        $usuarios = User::orderBy('name')->get();
        $categorias = Inventario::getCategorias();
        $estados = PrestamoInventario::getEstadosPrestamo();

        // Estadísticas para la vista
        $stats = [
            'activos' => PrestamoInventario::activos()->count(),
            'devueltos_mes' => PrestamoInventario::devueltos()
                                ->whereMonth('fecha_devolucion', now()->month)
                                ->whereYear('fecha_devolucion', now()->year)
                                ->count(),
            'vencidos' => PrestamoInventario::activos()
                           ->whereNotNull('fecha_devolucion_estimada')
                           ->where('fecha_devolucion_estimada', '<', now())
                           ->count(),
            'total' => PrestamoInventario::count(),
        ];

        return view('prestamos.index', compact('prestamos', 'usuarios', 'categorias', 'estados', 'stats'));
    }

    /**
     * Mostrar formulario para crear préstamo
     */
    public function create(Request $request)
    {
        $inventario_id = $request->get('inventario_id');
        $inventario = null;
        
        if ($inventario_id) {
            $inventario = Inventario::findOrFail($inventario_id);
            if (!$inventario->esta_disponible) {
                return redirect()->route('inventario.index')
                               ->with('error', 'Este artículo no está disponible para préstamo.');
            }
        }

        $usuarios = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $inventarios = Inventario::disponibles()->orderBy('articulo')->get();

        return view('prestamos.create', compact('usuarios', 'inventarios', 'inventario'));
    }

    /**
     * Guardar nuevo préstamo
=======

class PrestamoController extends Controller
{
    /**
     * Muestra el listado general de préstamos de inventario.
     */
    public function index()
    {
        return view('prestamos.index');
    }

    /**
     * Muestra el formulario para registrar un nuevo préstamo.
     */
    public function create()
    {
        return view('prestamos.create');
    }

    /**
     * Procesa el formulario de creación de un préstamo.
>>>>>>> 46950bdcfa1fa7f300c70cd6c64513c375117f2e
     */
    public function store(Request $request)
    {
        $request->validate([
<<<<<<< HEAD
            'inventario_id' => 'required|exists:inventarios,id',
            'user_id' => 'required|exists:users,id',
            'cantidad_prestada' => 'required|integer|min:1',
            'observaciones_prestamo' => 'nullable|string|max:1000',
        ]);

        $inventario = Inventario::findOrFail($request->inventario_id);

        // Verificar disponibilidad
        if ($request->cantidad_prestada > $inventario->cantidad_disponible) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', "Solo hay {$inventario->cantidad_disponible} unidades disponibles.");
        }

        try {
            $prestamo = new PrestamoInventario();
            $prestamo->inventario_id = $request->inventario_id;
            $prestamo->user_id = $request->user_id;
            $prestamo->cantidad_prestada = $request->cantidad_prestada;
            $prestamo->fecha_prestamo = now();
            $prestamo->observaciones_prestamo = $request->observaciones_prestamo;
            $prestamo->estado_prestamo = 'activo';
            $prestamo->prestado_por = Auth::id();
            $prestamo->save();

            return redirect()->route('prestamos.index')
                           ->with('success', 'Préstamo registrado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al registrar el préstamo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles del préstamo
     */
    public function show($id)
    {
        $prestamo = PrestamoInventario::with(['inventario', 'usuario', 'prestadoPor', 'recibidoPor'])
                                    ->findOrFail($id);

        return view('prestamos.show', compact('prestamo'));
    }

    /**
     * Mostrar formulario de devolución
     */
    public function devolver($id)
    {
        $prestamo = PrestamoInventario::with(['inventario', 'usuario'])
                                    ->where('estado_prestamo', 'activo')
                                    ->findOrFail($id);

        return view('prestamos.devolver', compact('prestamo'));
    }

    /**
     * Procesar devolución
     */
    public function procesarDevolucion(Request $request, $id)
    {
        $prestamo = PrestamoInventario::where('estado_prestamo', 'activo')
                                    ->findOrFail($id);

        $request->validate([
            'estado_devolucion' => 'required|in:devuelto,perdido,dañado',
            'observaciones_devolucion' => 'nullable|string|max:1000',
        ]);

        try {
            $prestamo->estado_prestamo = $request->estado_devolucion;
            $prestamo->fecha_devolucion = now();
            $prestamo->observaciones_devolucion = $request->observaciones_devolucion;
            $prestamo->recibido_por = Auth::id();
            $prestamo->save();

            // Si el artículo fue perdido o dañado, ajustar el inventario
            if (in_array($request->estado_devolucion, ['perdido', 'dañado'])) {
                $inventario = $prestamo->inventario;
                
                if ($request->estado_devolucion === 'perdido') {
                    // Reducir la cantidad del inventario
                    $inventario->cantidad -= $prestamo->cantidad_prestada;
                } elseif ($request->estado_devolucion === 'dañado') {
                    // Marcar como dañado si era el único
                    if ($inventario->cantidad == 1) {
                        $inventario->estado = 'dañado';
                    }
                }
                
                $inventario->save();
            }

            $mensaje = [
                'devuelto' => 'Artículo devuelto correctamente.',
                'perdido' => 'Artículo marcado como perdido.',
                'dañado' => 'Artículo marcado como dañado.',
            ];

            return redirect()->route('prestamos.index')
                           ->with('success', $mensaje[$request->estado_devolucion]);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al procesar la devolución: ' . $e->getMessage());
        }
    }

    /**
     * Obtener disponibilidad de inventario (AJAX)
     */
    public function getDisponibilidad($inventario_id)
    {
        $inventario = Inventario::findOrFail($inventario_id);
        
        return response()->json([
            'cantidad_total' => $inventario->cantidad,
            'cantidad_disponible' => $inventario->cantidad_disponible,
            'esta_disponible' => $inventario->esta_disponible,
        ]);
    }

    /**
     * Préstamos de un usuario específico
     */
    public function prestamosUsuario($user_id)
    {
        $usuario = User::findOrFail($user_id);
        $prestamos = PrestamoInventario::with(['inventario'])
                                     ->where('user_id', $user_id)
                                     ->orderBy('fecha_prestamo', 'desc')
                                     ->paginate(10);

        return view('prestamos.usuario', compact('usuario', 'prestamos'));
=======
            'solicitante' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_devolucion_estimada' => ['nullable', 'date'],
        ]);

        return redirect()
            ->route('prestamos.index')
            ->with('status', 'Préstamo registrado correctamente (vista demostrativa).');
    }

    /**
     * Muestra la información resumida de un préstamo específico.
     */
    public function show(string $prestamo)
    {
        return view('prestamos.show', [
            'prestamoId' => $prestamo,
        ]);
    }

    /**
     * Muestra el formulario para editar un préstamo específico.
     */
    public function edit(string $prestamo)
    {
        return view('prestamos.edit', [
            'prestamoId' => $prestamo,
        ]);
    }

    /**
     * Procesa la actualización de un préstamo existente.
     */
    public function update(Request $request, string $prestamo)
    {
        $request->validate([
            'solicitante' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_devolucion_estimada' => ['nullable', 'date'],
        ]);

        return redirect()
            ->route('prestamos.show', $prestamo)
            ->with('status', 'Préstamo actualizado correctamente (vista demostrativa).');
    }

    /**
     * Elimina (de forma demostrativa) un préstamo registrado.
     */
    public function destroy(string $prestamo)
    {
        return redirect()
            ->route('prestamos.index')
            ->with('status', 'Préstamo eliminado correctamente (vista demostrativa).');
>>>>>>> 46950bdcfa1fa7f300c70cd6c64513c375117f2e
    }
}
