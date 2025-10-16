<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestamoInventario extends Model
{
    use HasFactory;

    protected $table = 'prestamos_inventario';

    protected $fillable = [
        'inventario_id',
        'user_id',
        'cantidad_prestada',
        'fecha_prestamo',
        'fecha_devolucion',
        'observaciones_prestamo',
        'observaciones_devolucion',
        'estado_prestamo',
        'prestado_por',
        'recibido_por'
    ];

    protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_devolucion' => 'datetime',
        'cantidad_prestada' => 'integer'
    ];

    // Definir los estados de préstamo disponibles
    public static function getEstadosPrestamo()
    {
        return [
            'activo' => 'Activo',
            'devuelto' => 'Devuelto',
            'perdido' => 'Perdido',
            'dañado' => 'Dañado'
        ];
    }

    // Relación con el inventario
    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    // Relación con el usuario que tiene el préstamo
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el admin que prestó
    public function prestadoPor()
    {
        return $this->belongsTo(User::class, 'prestado_por');
    }

    // Relación con el admin que recibió
    public function recibidoPor()
    {
        return $this->belongsTo(User::class, 'recibido_por');
    }

    // Scope para préstamos activos
    public function scopeActivos($query)
    {
        return $query->where('estado_prestamo', 'activo');
    }

    // Scope para préstamos devueltos
    public function scopeDevueltos($query)
    {
        return $query->where('estado_prestamo', 'devuelto');
    }

    // Scope para préstamos de un usuario específico
    public function scopeDeUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Verificar si está activo
    public function getEsActivoAttribute()
    {
        return $this->estado_prestamo === 'activo';
    }

    // Obtener días transcurridos desde el préstamo
    public function getDiasPrestamoAttribute()
    {
        if ($this->fecha_devolucion) {
            return $this->fecha_prestamo->diffInDays($this->fecha_devolucion);
        }
        return $this->fecha_prestamo->diffInDays(now());
    }

    // Obtener el estado formateado
    public function getEstadoFormateadoAttribute()
    {
        $estados = self::getEstadosPrestamo();
        return $estados[$this->estado_prestamo] ?? $this->estado_prestamo;
    }

    // Marcar como devuelto
    public function marcarComoDevuelto($recibidoPor, $observaciones = null)
    {
        $this->update([
            'estado_prestamo' => 'devuelto',
            'fecha_devolucion' => now(),
            'recibido_por' => $recibidoPor,
            'observaciones_devolucion' => $observaciones
        ]);
    }
}
