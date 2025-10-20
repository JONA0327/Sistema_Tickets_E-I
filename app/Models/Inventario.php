<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_inventario',
        'categoria',
        'articulo',
        'modelo',
        'cantidad',
        'estado',
        'observaciones',
        'imagenes',
        'password_computadora',
        'anos_uso',
        'created_by',
        // Campos para discos con información
        'tiene_informacion',
        'informacion_contenido',
        'nivel_confidencialidad',
        'bloqueado_prestamo',
        'razon_bloqueo'
    ];

    protected $casts = [
        'imagenes' => 'array',
        'anos_uso' => 'integer',
        'cantidad' => 'integer',
        'tiene_informacion' => 'boolean',
        'bloqueado_prestamo' => 'boolean'
    ];

    // Definir las categorías disponibles
    public static function getCategorias()
    {
        return [
            'mouse' => 'Mouse',
            'discos_duros' => 'Discos Duros',
            'memorias_ram' => 'Memorias RAM',
            'cargadores' => 'Cargadores',
            'baterias' => 'Baterías',
            'computadoras' => 'Computadoras',
            'otros' => 'Otros'
        ];
    }

    // Definir los niveles de confidencialidad disponibles
    public static function getNivelesConfidencialidad()
    {
        return [
            'bajo' => 'Bajo - Información general',
            'medio' => 'Medio - Información sensible',
            'alto' => 'Alto - Información confidencial',
            'critico' => 'Crítico - Información clasificada'
        ];
    }

    // Definir los estados disponibles
    public static function getEstados()
    {
        return [
            'nuevo' => 'Nuevo',
            'usado' => 'Usado',
            'dañado' => 'Dañado'
        ];
    }

    // Relación con el usuario que creó el artículo
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relación con préstamos
    public function prestamos()
    {
        return $this->hasMany(PrestamoInventario::class);
    }

    // Préstamos activos
    public function prestamosActivos()
    {
        return $this->hasMany(PrestamoInventario::class)->where('estado_prestamo', 'activo');
    }

    // Relación con discos en uso (para discos duros)
    public function discoEnUso()
    {
        return $this->hasOne(DiscoEnUso::class)->where('esta_activo', true);
    }

    // Historial de uso de discos
    public function historialDiscosUso()
    {
        return $this->hasMany(DiscoEnUso::class);
    }

    // Cantidad disponible para unidades individuales
    public function getCantidadDisponibleAttribute()
    {
        // Para unidades individuales, la cantidad base es siempre 1
        $cantidadPrestada = (int) $this->prestamosActivos()->sum('cantidad_prestada');
        $cantidadBase = (int) $this->cantidad - $cantidadPrestada;
        
        // Si el estado es dañado, no está disponible
        if ($this->estado == 'dañado') {
            return 0;
        }
        
        return max(0, $cantidadBase);
    }

    // Verificar si está disponible para préstamo
    public function getEstaDisponibleAttribute()
    {
        // Si está bloqueado para préstamo, no está disponible
        if ($this->bloqueado_prestamo) {
            return false;
        }

        // Si es un disco duro y está en uso, no está disponible para préstamo
        if ($this->categoria === 'discos_duros' && $this->discoEnUso) {
            return false;
        }
        
        return $this->cantidad_disponible > 0 && in_array($this->estado, ['nuevo', 'usado']);
    }

    // Verificar si es un disco con información
    public function getEsDiscoConInformacionAttribute()
    {
        return $this->categoria === 'discos_duros' && $this->tiene_informacion;
    }

    // Obtener el color del nivel de confidencialidad
    public function getColorConfidencialidadAttribute()
    {
        return match($this->nivel_confidencialidad) {
            'bajo' => 'text-green-600 bg-green-50',
            'medio' => 'text-yellow-600 bg-yellow-50',
            'alto' => 'text-orange-600 bg-orange-50', 
            'critico' => 'text-red-600 bg-red-50',
            default => 'text-gray-600 bg-gray-50'
        };
    }

    // Scope para filtrar por categoría
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // Scope para filtrar por estado
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Scope para artículos funcionales (no dañados)
    public function scopeFuncionales($query)
    {
        return $query->whereIn('estado', ['nuevo', 'usado']);
    }

    // Scope para artículos disponibles
    public function scopeDisponibles($query)
    {
        return $query->funcionales()->whereRaw('cantidad > (
            SELECT COALESCE(SUM(cantidad_prestada), 0) 
            FROM prestamos_inventario 
            WHERE inventario_id = inventarios.id 
            AND estado_prestamo = "activo"
        )');
    }

    // Obtener el nombre formateado de la categoría
    public function getCategoriaFormateadaAttribute()
    {
        $categorias = self::getCategorias();
        return $categorias[$this->categoria] ?? $this->categoria;
    }

    // Obtener el nombre formateado del estado
    public function getEstadoFormateadoAttribute()
    {
        $estados = self::getEstados();
        return $estados[$this->estado] ?? $this->estado;
    }

    // Verificar si es una computadora
    public function getEsComputadoraAttribute()
    {
        return $this->categoria === 'computadoras';
    }

    /**
     * Generar código de inventario personalizado por categoría
     */
    public static function generarCodigoInventario($categoria)
    {
        $prefijos = [
            'mouse' => 'MOU',
            'discos_duros' => 'DDU',
            'memorias_ram' => 'RAM',
            'cargadores' => 'CAR',
            'baterias' => 'BAT',
            'computadoras' => 'COM',
            'otros' => 'OTR'
        ];

        $prefijo = $prefijos[$categoria] ?? 'INV';
        
        // Buscar el último número de la categoría
        $ultimoCodigo = self::where('codigo_inventario', 'LIKE', $prefijo . '%')
            ->orderBy('codigo_inventario', 'desc')
            ->first();

        if ($ultimoCodigo && preg_match('/(\d+)$/', $ultimoCodigo->codigo_inventario, $matches)) {
            $ultimoNumero = intval($matches[1]);
        } else {
            $ultimoNumero = 0;
        }

        $nuevoNumero = $ultimoNumero + 1;
        
        // Formatear con ceros a la izquierda (3 dígitos)
        return $prefijo . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener los prefijos de categorías disponibles
     */
    public static function getPrejiosCategorias()
    {
        return [
            'mouse' => 'MOU',
            'discos_duros' => 'DDU',
            'memorias_ram' => 'RAM',
            'cargadores' => 'CAR',
            'baterias' => 'BAT',
            'computadoras' => 'COM',
            'otros' => 'OTR'
        ];
    }

    /**
     * Buscar artículos similares (misma categoría, artículo y modelo)
     */
    public static function buscarSimilares($categoria, $articulo, $modelo)
    {
        return self::where('categoria', $categoria)
                   ->where('articulo', $articulo)
                   ->where('modelo', $modelo)
                   ->orderBy('codigo_inventario')
                   ->get();
    }

    /**
     * Crear múltiples unidades del mismo artículo
     */
    public static function crearMultiplesUnidades($data, $cantidad = 1)
    {
        $unidades = [];
        
        for ($i = 0; $i < $cantidad; $i++) {
            $unidad = new self($data);
            $unidad->cantidad = 1; // Cada unidad tiene cantidad 1
            $unidad->save();
            $unidades[] = $unidad;
        }
        
        return $unidades;
    }

    /**
     * Obtener todas las unidades del mismo grupo (mismo artículo y modelo)
     */
    public function getUnidadesDelGrupoAttribute()
    {
        return self::buscarSimilares($this->categoria, $this->articulo, $this->modelo);
    }

    /**
     * Obtener el total de unidades en el grupo
     */
    public function getTotalUnidadesGrupoAttribute()
    {
        return $this->unidades_del_grupo->count();
    }

    /**
     * Obtener unidades disponibles en el grupo
     */
    public function getUnidadesDisponiblesGrupoAttribute()
    {
        return $this->unidades_del_grupo->filter(function($unidad) {
            return $unidad->cantidad_disponible > 0;
        })->count();
    }

    /**
     * Buscar unidades similares (misma categoría, artículo y modelo)
     */
    public function getUnidadesSimilaresAttribute()
    {
        return self::where('categoria', $this->categoria)
            ->where('articulo', $this->articulo)
            ->where('modelo', $this->modelo)
            ->where('id', '!=', $this->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Obtener todas las unidades del grupo incluyendo esta
     */
    public function getGrupoCompletaAttribute()
    {
        return self::where('categoria', $this->categoria)
            ->where('articulo', $this->articulo)
            ->where('modelo', $this->modelo)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Contar total de unidades del grupo
     */
    public function getTotalGrupoAttribute()
    {
        return $this->grupo_completa->count();
    }

    /**
     * Contar unidades funcionales del grupo
     */
    public function getFuncionalesGrupoAttribute()
    {
        return $this->grupo_completa->whereIn('estado', ['nuevo', 'usado'])->count();
    }

    /**
     * Contar unidades disponibles del grupo
     */
    public function getDisponiblesGrupoAttribute()
    {
        return $this->grupo_completa->filter(function($unidad) {
            return $unidad->esta_disponible;
        })->count();
    }

    /**
     * Boot method para generar código automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventario) {
            if (empty($inventario->codigo_inventario)) {
                $inventario->codigo_inventario = self::generarCodigoInventario($inventario->categoria);
            }
        });
    }
}
