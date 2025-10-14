<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemaArchivo extends Model
{
    use HasFactory;

    protected $table = 'problema_archivo';

    protected $fillable = [
        'ticket_id',
        'categoria',
        'titulo',
        'descripcion_problema',
        'solucion',
        'solucion_imagenes',
        'tipo_problema',
        'palabras_clave',
        'frecuencia',
        'notas_adicionales',
        'archivado_por',
        'fecha_archivo'
    ];

    protected $casts = [
        'palabras_clave' => 'array',
        'solucion_imagenes' => 'array',
        'fecha_archivo' => 'datetime'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function archivadoPor()
    {
        return $this->belongsTo(User::class, 'archivado_por');
    }

    // Scope para buscar por categoría
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // Scope para buscar por tipo de problema
    public function scopeTipoProblema($query, $tipo)
    {
        return $query->where('tipo_problema', $tipo);
    }

    // Scope para búsqueda por palabras clave
    public function scopeBuscarPalabras($query, $palabras)
    {
        return $query->where(function ($q) use ($palabras) {
            foreach ((array) $palabras as $palabra) {
                $q->orWhere('titulo', 'LIKE', "%{$palabra}%")
                  ->orWhere('descripcion_problema', 'LIKE', "%{$palabra}%")
                  ->orWhere('solucion', 'LIKE', "%{$palabra}%")
                  ->orWhereJsonContains('palabras_clave', $palabra);
            }
        });
    }
}
