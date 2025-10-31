<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchivoProblemasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscoEnUsoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rutas protegidas de tickets (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/ticket/create/{tipo}', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/ticket', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/mis-tickets', [TicketController::class, 'misTickets'])->name('tickets.mis-tickets');
    Route::delete('/ticket/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/ticket/{ticket}/acknowledge-update', [TicketController::class, 'acknowledgeUpdate'])->name('tickets.acknowledge');
    Route::post('/tickets/acknowledge-all', [TicketController::class, 'acknowledgeAllUpdates'])->name('tickets.acknowledge-all');

    Route::get('/maintenance/availability', [MaintenanceController::class, 'availability'])->name('maintenance.availability');
    Route::get('/maintenance/slots', [MaintenanceController::class, 'slots'])->name('maintenance.slots');

});

// Rutas del archivo de problemas (solo administradores)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/archivo-problemas', [ArchivoProblemasController::class, 'index'])->name('archivo-problemas.index');
    Route::get('/archivo-problemas/crear/{ticket?}', [ArchivoProblemasController::class, 'create'])->name('archivo-problemas.create');
    Route::post('/archivo-problemas', [ArchivoProblemasController::class, 'store'])->name('archivo-problemas.store');
    Route::post('/archivo-problemas/crear-ticket-y-archivar', [ArchivoProblemasController::class, 'createTicketAndArchive'])->name('archivo-problemas.create-ticket-and-archive');
    Route::get('/archivo-problemas/{problema}', [ArchivoProblemasController::class, 'show'])->name('archivo-problemas.show');
    Route::get('/archivo-problemas/{problema}/editar', [ArchivoProblemasController::class, 'edit'])->name('archivo-problemas.edit');
    Route::put('/archivo-problemas/{problema}', [ArchivoProblemasController::class, 'update'])->name('archivo-problemas.update');
    Route::delete('/archivo-problemas/{problema}', [ArchivoProblemasController::class, 'destroy'])->name('archivo-problemas.destroy');
    Route::get('/archivo-problemas-estadisticas', [ArchivoProblemasController::class, 'estadisticas'])->name('archivo-problemas.estadisticas');
});

// Rutas de administración
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{ticket}/change-maintenance-date', [TicketController::class, 'changeMaintenanceDate'])->name('tickets.change-maintenance-date');
    Route::get('/maintenance-slots/available', [TicketController::class, 'getAvailableMaintenanceSlots'])->name('maintenance-slots.available');
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');
    Route::get('/inventory-requests', [AdminController::class, 'inventoryRequests'])->name('inventory-requests');

    Route::get('/maintenance', [MaintenanceController::class, 'adminIndex'])->name('maintenance.index');
    Route::post('/maintenance/slots', [MaintenanceController::class, 'storeSlot'])->name('maintenance.slots.store');
    Route::post('/maintenance/slots/bulk', [MaintenanceController::class, 'storeBulkSlots'])->name('maintenance.slots.store-bulk');
    Route::put('/maintenance/slots/{slot}', [MaintenanceController::class, 'updateSlot'])->name('maintenance.slots.update');
    Route::delete('/maintenance/slots/past', [MaintenanceController::class, 'destroyPastSlots'])->name('maintenance.slots.destroy-past');
    Route::delete('/maintenance/slots/{slot}', [MaintenanceController::class, 'destroySlot'])->name('maintenance.slots.destroy');
    Route::get('/maintenance/computers', [MaintenanceController::class, 'computersIndex'])->name('maintenance.computers.index');
    Route::patch('/maintenance/computers/{profile}', [MaintenanceController::class, 'updateComputerLoan'])->name('maintenance.computers.update-loan');
    
    // Gestión de usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::delete('/users/{user}/rejection', [AdminController::class, 'destroyRejectedUser'])->name('users.rejections.destroy');
    Route::delete('/blocked-emails/{blockedEmail}', [AdminController::class, 'destroyBlockedEmail'])->name('blocked-emails.destroy');
    
    // Manual de Ayuda - Administración
    Route::get('/help', [\App\Http\Controllers\HelpController::class, 'adminIndex'])->name('help.index');
    Route::get('/help/create', [\App\Http\Controllers\HelpController::class, 'create'])->name('help.create');
    Route::post('/help', [\App\Http\Controllers\HelpController::class, 'store'])->name('help.store');
    Route::get('/help/{helpSection}/edit', [\App\Http\Controllers\HelpController::class, 'edit'])->name('help.edit');
    Route::put('/help/{helpSection}', [\App\Http\Controllers\HelpController::class, 'update'])->name('help.update');
    Route::delete('/help/{helpSection}', [\App\Http\Controllers\HelpController::class, 'destroy'])->name('help.destroy');
    Route::patch('/help/{helpSection}/toggle', [\App\Http\Controllers\HelpController::class, 'toggleStatus'])->name('help.toggle');
    
    // Gestión de imágenes del manual de ayuda
    Route::post('/help/{helpSection}/upload-image', [\App\Http\Controllers\HelpController::class, 'uploadImage'])->name('help.upload-image');
    Route::delete('/help/{helpSection}/delete-image/{imageIndex}', [\App\Http\Controllers\HelpController::class, 'deleteImage'])->name('help.delete-image');
});

// API Routes for Notifications (Admin only)
Route::middleware(['auth', 'admin'])->prefix('api')->group(function () {
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount']);
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadTickets']);
    Route::post('/notifications/{ticket}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/stats', [NotificationController::class, 'getStats']);
});

// Rutas de inventario - solo administradores (DEBEN IR PRIMERO para evitar conflictos)
Route::middleware(['auth', 'admin'])->group(function () {
    // CRUD de inventario
    Route::get('/inventario/crear', [InventarioController::class, 'create'])->name('inventario.create');
    Route::get('/inventario/{inventario}/editar', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::put('/inventario/{inventario}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::delete('/inventario/{inventario}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
    Route::delete('/inventario/{inventario}/eliminar-imagen', [InventarioController::class, 'eliminarImagen'])->name('inventario.eliminar-imagen');
    
    // Gestión de préstamos
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/prestamos/crear', [PrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
    Route::get('/prestamos/{prestamo}', [PrestamoController::class, 'show'])->name('prestamos.show');
    Route::get('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('prestamos.devolver');
    Route::put('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'procesarDevolucion'])->name('prestamos.procesar-devolucion');
    Route::get('/prestamos/usuario/{user}', [PrestamoController::class, 'prestamosUsuario'])->name('prestamos.usuario');
    
    // AJAX routes
    Route::get('/api/inventario/{inventario}/disponibilidad', [PrestamoController::class, 'getDisponibilidad'])->name('inventario.disponibilidad');
    
    // Gestión de discos en uso
    Route::get('/discos-en-uso', [DiscoEnUsoController::class, 'index'])->name('discos-en-uso.index');
    Route::get('/discos-en-uso/crear', [DiscoEnUsoController::class, 'create'])->name('discos-en-uso.create');
    Route::post('/discos-en-uso', [DiscoEnUsoController::class, 'store'])->name('discos-en-uso.store');
    Route::get('/discos-en-uso/{discoEnUso}', [DiscoEnUsoController::class, 'show'])->name('discos-en-uso.show');
    Route::get('/discos-en-uso/{discoEnUso}/retirar', [DiscoEnUsoController::class, 'retirar'])->name('discos-en-uso.retirar');
    Route::put('/discos-en-uso/{discoEnUso}/retirar', [DiscoEnUsoController::class, 'procesarRetiro'])->name('discos-en-uso.procesar-retiro');
});

// Rutas de inventario (accesibles para usuarios autenticados - visualización)
Route::middleware('auth')->group(function () {
    // Inventario - visualización para todos
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/{inventario}', [InventarioController::class, 'show'])->name('inventario.show');
    
    // Préstamos - ver préstamos de usuario  
    Route::get('/mis-prestamos/{user?}', [PrestamoController::class, 'prestamosUsuario'])->name('mis-prestamos');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Manual de Ayuda - Vista pública (disponible para usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/ayuda', [\App\Http\Controllers\HelpController::class, 'index'])->name('help.public');
});

require __DIR__.'/auth.php';
