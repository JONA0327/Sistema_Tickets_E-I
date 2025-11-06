<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HelpSection extends Model
{
    protected $fillable = [
        'title',
        'content',
        'images',
        'section_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Scope para obtener solo secciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por section_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('section_order', 'asc')->orderBy('created_at', 'asc');
    }

    /**
     * Obtener secciones activas y ordenadas
     */
    public function scopeActiveOrdered($query)
    {
        return $query->active()->ordered();
    }

    /**
     * Obtener URL completa de una imagen
     */
    public function getImageUrl($imageName)
    {
        // Buscar por filename o por reference en las imágenes almacenadas
        $images = $this->images ?? [];
        foreach ($images as $image) {
            if ((isset($image['filename']) && $image['filename'] === $imageName) || (isset($image['reference']) && $image['reference'] === $imageName)) {
                // Si tenemos data URI almacenada, devolverla directamente
                if (isset($image['data'])) {
                    return $image['data'];
                }
                // Si no, intentar devolver la ruta legacy
                if (isset($image['filename'])) {
                    return route('help.images.show', ['filename' => $image['filename']]);
                }
            }
        }

        // Fallback: assume $imageName is a filename and return route
        return route('help.images.show', ['filename' => $imageName]);
    }

    /**
     * Obtener todas las URLs de imágenes
     */
    public function getImageUrls()
    {
        if (!$this->images) {
            return [];
        }

        return collect($this->images)->mapWithKeys(function ($image) {
            $key = $image['reference'] ?? ($image['filename'] ?? Str::random(8));
            // Preferir data URI en 'data'
            if (isset($image['data'])) {
                return [$key => $image['data']];
            }
            if (isset($image['filename'])) {
                return [$key => $this->getImageUrl($image['filename'])];
            }
            return [$key => null];
        })->toArray();
    }

    /**
     * Procesar contenido reemplazando referencias de imágenes
     */
    public function getProcessedContent()
    {
        $content = $this->content;
        $imageUrls = $this->getImageUrls();

        foreach ($imageUrls as $reference => $url) {
            // Reemplazar referencias como [img:figura1] con <img> tags
            $pattern = '/\[img:' . preg_quote($reference, '/') . '\]/i';
            $replacement = '<div class="my-6 text-center"><img src="' . $url . '" alt="' . $reference . '" class="max-w-full h-auto rounded-lg shadow-lg mx-auto" loading="lazy" style="max-height: 500px;"><p class="text-sm text-gray-500 mt-2 italic">Figura: ' . $reference . '</p></div>';
            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }

    /**
     * Generar referencia única para nueva imagen
     */
    public function generateImageReference($originalName)
    {
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^a-zA-Z0-9]/', '', $baseName);
        $baseName = strtolower(substr($baseName, 0, 10));
        
        $counter = 1;
        $reference = 'figura' . $counter;
        $existingReferences = collect($this->images ?? [])->pluck('reference')->toArray();
        
        while (in_array($reference, $existingReferences)) {
            $counter++;
            $reference = 'figura' . $counter;
        }
        
        return $reference;
    }
}
