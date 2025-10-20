<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'user_id',
        'nombre_solicitante',
        'correo_solicitante',
        'nombre_programa',
        'descripcion_problema',
        'imagenes',
        'estado',
        'fecha_apertura',
        'fecha_cierre',
        'observaciones',
        'tipo_problema',
        'prioridad',
        'is_read',
        'notified_at',
        'read_at'
    ];

    protected $casts = [
        'imagenes' => 'array',
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'notified_at' => 'datetime',
        'read_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generar folio automático al crear
        static::creating(function ($ticket) {
            if (empty($ticket->folio)) {
                $ticket->folio = static::generateFolio();
            }
        });
    }

    /**
     * Generar folio único
     */
    public static function generateFolio()
    {
        $year = date('Y');
        $month = date('m');
        
        // Buscar el último ticket del mes actual
        $lastTicket = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastTicket ? (intval(substr($lastTicket->folio, -4)) + 1) : 1;
        
        return sprintf('TK%s%s%04d', $year, $month, $number);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeByEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeByTipo($query, $tipo)
    {
        return $query->where('tipo_problema', $tipo);
    }

    /**
     * Obtener el badge de estado
     */
    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'abierto' => 'bg-red-100 text-red-800',
            'en_proceso' => 'bg-yellow-100 text-yellow-800',
            'cerrado' => 'bg-green-100 text-green-800'
        ];

        return $badges[$this->estado] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Obtener el badge de prioridad
     */
    public function getPrioridadBadgeAttribute()
    {
        $badges = [
            'baja' => 'bg-blue-100 text-blue-800',
            'media' => 'bg-orange-100 text-orange-800',
            'alta' => 'bg-red-100 text-red-800',
            'critica' => 'bg-red-600 text-white'
        ];

        return $badges[$this->prioridad] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Relación con el usuario que creó el ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
