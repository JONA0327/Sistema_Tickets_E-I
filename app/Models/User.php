<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relación con tickets
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Relación con préstamos activos de inventario
     */
    public function prestamosActivos()
    {
        return $this->hasMany(PrestamoInventario::class, 'user_id')->where('estado_prestamo', 'activo');
    }

    /**
     * Relación con todos los préstamos de inventario
     */
    public function prestamosInventario()
    {
        return $this->hasMany(PrestamoInventario::class, 'user_id');
    }

    /**
     * Relación con inventarios creados por el usuario
     */
    public function inventariosCreados()
    {
        return $this->hasMany(Inventario::class, 'created_by');
    }
}
