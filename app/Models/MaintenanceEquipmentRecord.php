<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceEquipmentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'usuario_nombre',
        'usuario_correo',
        'equipo_marca',
        'equipo_modelo',
        'equipo_tipo_disco',
        'equipo_ram_capacidad',
        'equipo_observaciones_esteticas',
        'equipo_bateria_estado',
        'maintenance_cierre_observaciones',
        'maintenance_reporte',
        'maintenance_componentes_reemplazo',
        'mantenimiento_programado',
        'prestado',
        'prestado_a_nombre',
        'prestado_a_correo',
    ];

    protected $casts = [
        'maintenance_componentes_reemplazo' => 'array',
        'mantenimiento_programado' => 'datetime',
        'prestado' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
