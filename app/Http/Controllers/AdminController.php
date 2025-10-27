<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockedEmail;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Mostrar el panel de administración
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Mostrar la gestión de tickets
     */
    public function tickets()
    {
        // Por implementar
        return view('admin.tickets');
    }

    /**
     * Mostrar el inventario
     */
    public function inventory(Request $request)
    {
        $filters = [
            'solo_funcionales' => $request->boolean('solo_funcionales'),
            'solo_disponibles' => $request->boolean('solo_disponibles'),
        ];

        $query = InventoryItem::query();

        if ($filters['solo_funcionales']) {
            $query->where('es_funcional', true);
        }

        if ($filters['solo_disponibles']) {
            $query->where('estado', InventoryItem::ESTADO_DISPONIBLE);
        }

        $items = $query
            ->orderBy('codigo_producto')
            ->orderBy('identificador')
            ->orderBy('id')
            ->get();

        $groupedInventory = $items
            ->groupBy('codigo_producto')
            ->map(function ($items) {
                /** @var \App\Models\InventoryItem $first */
                $first = $items->first();

                $stateCounts = [
                    InventoryItem::ESTADO_DISPONIBLE => $items->where('estado', InventoryItem::ESTADO_DISPONIBLE)->count(),
                    InventoryItem::ESTADO_PRESTADO => $items->where('estado', InventoryItem::ESTADO_PRESTADO)->count(),
                    InventoryItem::ESTADO_MANTENIMIENTO => $items->where('estado', InventoryItem::ESTADO_MANTENIMIENTO)->count(),
                    InventoryItem::ESTADO_RESERVADO => $items->where('estado', InventoryItem::ESTADO_RESERVADO)->count(),
                    InventoryItem::ESTADO_DANADO => $items->where('estado', InventoryItem::ESTADO_DANADO)->count(),
                ];

                return [
                    'codigo_producto' => $first->codigo_producto,
                    'nombre' => $first->nombre,
                    'categoria' => $first->categoria,
                    'marca' => $first->marca,
                    'modelo' => $first->modelo,
                    'descripcion_general' => $first->descripcion_general,
                    'total' => $items->count(),
                    'stateCounts' => $stateCounts,
                    'items' => $items,
                ];
            })
            ->values();

        $globalStats = [
            'total' => InventoryItem::count(),
            'funcionales' => InventoryItem::where('es_funcional', true)->count(),
            'disponibles' => InventoryItem::where('estado', InventoryItem::ESTADO_DISPONIBLE)->count(),
            'danados' => InventoryItem::where('estado', InventoryItem::ESTADO_DANADO)->count(),
        ];

        return view('admin.inventory', [
            'inventoryGroups' => $groupedInventory,
            'filters' => $filters,
            'globalStats' => $globalStats,
            'estadoLabels' => InventoryItem::estadoLabels(),
        ]);
    }

    /**
     * Mostrar las solicitudes de inventario
     */
    public function inventoryRequests()
    {
        return redirect()->route('prestamos.index');
    }

    /**
     * Mostrar la gestión de usuarios
     */
    public function users()
    {
        $approvedUsers = User::where('status', User::STATUS_APPROVED)
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'approved_page');

        $pendingUsers = User::where('status', User::STATUS_PENDING)
            ->orderBy('created_at')
            ->get();

        $rejectedUsers = User::where('status', User::STATUS_REJECTED)
            ->orderByDesc('rejected_at')
            ->get();

        $blockedEmails = BlockedEmail::orderByDesc('created_at')->get();

        return view('admin.users.index', compact('approvedUsers', 'pendingUsers', 'rejectedUsers', 'blockedEmails'));
    }

    /**
     * Mostrar formulario para crear nuevo usuario
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Crear nuevo usuario
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'ends_with:estrategiaeinnovacion.com.mx',
            ],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.ends_with' => 'El correo debe pertenecer al dominio estrategiaeinnovacion.com.mx.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'role.required' => 'Debe seleccionar un rol.',
            'role.in' => 'El rol seleccionado no es válido.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
            'status' => User::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar detalles del usuario con su historial completo
     */
    public function showUser(User $user)
    {
        // Cargar tickets del usuario con información completa
        $tickets = $user->tickets()
            ->orderBy('created_at', 'desc')
            ->get();

        // Cargar préstamos del usuario
        $prestamos = $user->prestamosInventario()
            ->with('inventario')
            ->orderBy('created_at', 'desc')
            ->get();

        // Estadísticas del usuario
        $stats = [
            'total_tickets' => $tickets->count(),
            'tickets_abiertos' => $tickets->where('estado', 'abierto')->count(),
            'tickets_en_proceso' => $tickets->where('estado', 'en_proceso')->count(),
            'tickets_cerrados' => $tickets->whereIn('estado', ['cerrado', 'cerrados'])->count(),
            'total_prestamos' => $prestamos->count(),
            'prestamos_activos' => $prestamos->where('estado_prestamo', 'activo')->count(),
            'prestamos_devueltos' => $prestamos->where('estado_prestamo', 'devuelto')->count(),
        ];

        return view('admin.users.show', compact('user', 'tickets', 'prestamos', 'stats'));
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualizar usuario - Permite cambiar nombre, email y contraseña
     * El rol se mantiene para preservar la seguridad del sistema
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email_prefix' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email_prefix.required' => 'La parte del correo antes del @ es obligatoria.',
            'email_prefix.max' => 'La parte del correo no puede exceder 100 caracteres.',
            'email_prefix.regex' => 'El correo solo puede contener letras, números, puntos, guiones y guiones bajos.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        // Construir el email completo
        $email = $request->email_prefix . '@estrategiaeinnovacion.com.mx';

        // Validar que el email no esté en uso por otro usuario
        if ($email !== $user->email && User::where('email', $email)->exists()) {
            return back()->withErrors(['email_prefix' => 'Este correo electrónico ya está registrado.'])->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $email,
        ];

        // Solo actualizar la contraseña si se proporciona
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroyUser(User $user)
    {
        // No permitir que un admin se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Eliminar registro de un usuario rechazado.
     */
    public function destroyRejectedUser(User $user)
    {
        if ($user->status !== User::STATUS_REJECTED) {
            return redirect()->route('admin.users')->with('error', 'Solo puedes eliminar solicitudes rechazadas.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Solicitud rechazada eliminada correctamente.');
    }

    /**
     * Eliminar correo bloqueado de la lista.
     */
    public function destroyBlockedEmail(BlockedEmail $blockedEmail)
    {
        $blockedEmail->delete();

        return redirect()->route('admin.users')->with('success', 'Correo desbloqueado correctamente.');
    }

    /**
     * Aprobar una solicitud de usuario pendiente.
     */
    public function approveUser(User $user)
    {
        if ($user->status !== User::STATUS_PENDING) {
            return redirect()->route('admin.users')->with('info', 'Este usuario ya fue procesado.');
        }

        $user->update([
            'status' => User::STATUS_APPROVED,
            'approved_at' => now(),
            'rejected_at' => null,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario aprobado y habilitado para acceder al sistema.');
    }

    /**
     * Rechazar una solicitud de usuario pendiente.
     */
    public function rejectUser(Request $request, User $user)
    {
        if ($user->status !== User::STATUS_PENDING) {
            return redirect()->route('admin.users')->with('info', 'Este usuario ya fue procesado.');
        }

        $data = $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedEmail::updateOrCreate(
            ['email' => $user->email],
            [
                'reason' => $data['reason'] ?? null,
                'blocked_by' => auth()->id(),
            ]
        );

        $user->update([
            'status' => User::STATUS_REJECTED,
            'rejected_at' => now(),
            'approved_at' => null,
            'email_verified_at' => null,
        ]);

        return redirect()->route('admin.users')->with('success', 'Solicitud rechazada y correo marcado como no permitido.');
    }
}
