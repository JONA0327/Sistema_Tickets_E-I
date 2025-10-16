<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchivoProblemasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas de tickets (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/ticket/create/{tipo}', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/ticket', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/mis-tickets', [TicketController::class, 'misTickets'])->name('tickets.mis-tickets');
    Route::delete('/ticket/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    Route::resource('prestamos', PrestamoController::class);
    
    // Rutas del archivo de problemas
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
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');
    Route::get('/inventory-requests', [AdminController::class, 'inventoryRequests'])->name('inventory-requests');
    
    // Gestión de usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
