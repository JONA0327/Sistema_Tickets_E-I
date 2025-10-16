<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InventoryItem extends Model
{
    use HasFactory;

    public const ESTADO_DISPONIBLE = 'disponible';
    public const ESTADO_PRESTADO = 'prestado';
    public const ESTADO_MANTENIMIENTO = 'mantenimiento';
    public const ESTADO_RESERVADO = 'reservado';
    public const ESTADO_DANADO = 'danado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo_producto',
        'identificador',
        'nombre',
        'categoria',
        'marca',
        'modelo',
        'numero_serie',
        'estado',
        'es_funcional',
        'descripcion_general',
        'notas',
        'ubicacion',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'es_funcional' => 'boolean',
    ];

    /**
     * Map of human readable labels per status.
     */
    public static function estadoLabels(): array
    {
        return [
            self::ESTADO_DISPONIBLE => 'Disponible',
            self::ESTADO_PRESTADO => 'Prestado',
            self::ESTADO_MANTENIMIENTO => 'En mantenimiento',
            self::ESTADO_RESERVADO => 'Reservado',
            self::ESTADO_DANADO => 'No disponible por daño',
        ];
    }

    /**
     * Tailwind badge styles for each status.
     */
    public static function estadoBadgeClasses(): array
    {
        return [
            self::ESTADO_DISPONIBLE => 'bg-green-100 text-green-800 border border-green-200',
            self::ESTADO_PRESTADO => 'bg-blue-100 text-blue-800 border border-blue-200',
            self::ESTADO_MANTENIMIENTO => 'bg-amber-100 text-amber-800 border border-amber-200',
            self::ESTADO_RESERVADO => 'bg-purple-100 text-purple-800 border border-purple-200',
            self::ESTADO_DANADO => 'bg-red-100 text-red-800 border border-red-200',
        ];
    }

    /**
     * Badge class attribute accessor.
     */
    public function getEstadoBadgeClassAttribute(): string
    {
        return static::estadoBadgeClasses()[$this->estado] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
    }

    /**
     * Human readable status label accessor.
     */
    public function getEstadoLabelAttribute(): string
    {
        return static::estadoLabels()[$this->estado] ?? Str::headline($this->estado);
    }

    /**
     * Returns a short text for the functional status.
     */
    public function getFuncionamientoLabelAttribute(): string
    {
        return $this->es_funcional ? 'Funcional' : 'No funcional';
    }
}
