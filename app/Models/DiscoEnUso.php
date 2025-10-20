<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscoEnUso extends Model
{
    use HasFactory;

    protected $table = 'discos_en_uso';

    protected $fillable = [
        'inventario_id',
        'nombre_computadora',
        'computadora_inventario_id',
        'razon_uso',
        'disco_reemplazado',
        'detalles_reemplazo',
        'fecha_instalacion',
        'esta_activo',
        'fecha_retiro',
        'observaciones',
        'instalado_por',
        'retirado_por'
    ];

    protected $casts = [
        'fecha_instalacion' => 'date',
        'fecha_retiro' => 'date',
        'esta_activo' => 'boolean'
    ];

    // Relación con el inventario (disco)
    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    // Relación con la computadora registrada (si existe)
    public function computadoraInventario()
    {
        return $this->belongsTo(Inventario::class, 'computadora_inventario_id');
    }

    // Relación con quien instaló el disco
    public function instaladoPor()
    {
        return $this->belongsTo(User::class, 'instalado_por');
    }

    // Relación con quien retiró el disco
    public function retiradoPor()
    {
        return $this->belongsTo(User::class, 'retirado_por');
    }

    // Scope para discos activos
    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    // Scope para discos retirados
    public function scopeRetirados($query)
    {
        return $query->where('esta_activo', false);
    }
}