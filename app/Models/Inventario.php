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
        'created_by'
    ];

    protected $casts = [
        'imagenes' => 'array',
        'anos_uso' => 'integer',
        'cantidad' => 'integer'
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

    // Cantidad disponible (cantidad total - cantidad prestada - unidades dañadas)
    public function getCantidadDisponibleAttribute()
    {
        $cantidadPrestada = $this->prestamosActivos()->sum('cantidad_prestada');
        $cantidadBase = $this->cantidad - $cantidadPrestada;
        
        // Si es múltiples unidades, restar las unidades dañadas
        if (str_contains($this->observaciones ?? '', '--- DETALLES POR UNIDAD ---')) {
            $unidadesDanadas = 0;
            $detalles = explode('--- DETALLES POR UNIDAD ---', $this->observaciones)[1] ?? '';
            
            if ($detalles) {
                $lineas = array_filter(explode("\n", $detalles));
                foreach ($lineas as $linea) {
                    if (str_contains($linea, 'UNIDAD') && str_contains($linea, 'Estado: Dañado')) {
                        $unidadesDanadas++;
                    }
                }
            }
            
            return max(0, $cantidadBase - $unidadesDanadas);
        }
        
        // Si el estado general es dañado y es unidad única
        if ($this->estado == 'dañado') {
            return 0;
        }
        
        return max(0, $cantidadBase);
    }

    // Verificar si está disponible para préstamo
    public function getEstaDisponibleAttribute()
    {
        return $this->cantidad_disponible > 0 && in_array($this->estado, ['nuevo', 'usado']);
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
