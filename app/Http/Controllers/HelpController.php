<?php

namespace App\Http\Controllers;

use App\Models\HelpSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HelpController extends Controller
{
    /**
     * Mostrar el manual de ayuda completo (vista pública)
     */
    public function index()
    {
        $sections = HelpSection::activeOrdered()->get();
        return view('help.index', compact('sections'));
    }

    /**
     * Panel de administración - Listar todas las secciones
     */
    public function adminIndex()
    {
        $sections = HelpSection::ordered()->get();
        return view('admin.help.index', compact('sections'));
    }

    /**
     * Mostrar formulario para crear nueva sección
     */
    public function create()
    {
        return view('admin.help.create');
    }

    /**
     * Guardar nueva sección
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'section_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:102400',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede exceder 255 caracteres.',
            'content.required' => 'El contenido es obligatorio.',
            'section_order.required' => 'El orden de la sección es obligatorio.',
            'section_order.integer' => 'El orden debe ser un número entero.',
            'section_order.min' => 'El orden debe ser mayor o igual a 0.',
            'images.*.image' => 'El archivo debe ser una imagen.',
            'images.*.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'La imagen no puede ser mayor a 100MB.',
        ]);

        // Crear la sección primero
        $helpSection = HelpSection::create([
            'title' => $request->title,
            'content' => $request->content,
            'section_order' => $request->section_order,
            'is_active' => $request->boolean('is_active', true),
            'images' => [],
        ]);

        // Procesar imágenes si existen
        if ($request->hasFile('images')) {
            $this->processImages($request, $helpSection);
        }

        return redirect()->route('admin.help.index')->with('success', 'Sección del manual creada exitosamente.');
    }

    /**
     * Mostrar formulario para editar sección
     */
    public function edit(HelpSection $helpSection)
    {
        return view('admin.help.edit', compact('helpSection'));
    }

    /**
     * Actualizar sección
     */
    public function update(Request $request, HelpSection $helpSection)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'section_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:102400',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede exceder 255 caracteres.',
            'content.required' => 'El contenido es obligatorio.',
            'section_order.required' => 'El orden de la sección es obligatorio.',
            'section_order.integer' => 'El orden debe ser un número entero.',
            'section_order.min' => 'El orden debe ser mayor o igual a 0.',
            'images.*.image' => 'El archivo debe ser una imagen.',
            'images.*.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'La imagen no puede ser mayor a 100MB.',
        ]);

        $helpSection->update([
            'title' => $request->title,
            'content' => $request->content,
            'section_order' => $request->section_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Procesar nuevas imágenes si existen
        if ($request->hasFile('images')) {
            $this->processImages($request, $helpSection);
        }

        return redirect()->route('admin.help.index')->with('success', 'Sección del manual actualizada exitosamente.');
    }

    /**
     * Eliminar sección
     */
    public function destroy(HelpSection $helpSection)
    {
        $helpSection->delete();
        return redirect()->route('admin.help.index')->with('success', 'Sección del manual eliminada exitosamente.');
    }

    /**
     * Cambiar estado activo/inactivo
     */
    public function toggleStatus(HelpSection $helpSection)
    {
        $helpSection->update([
            'is_active' => !$helpSection->is_active
        ]);

        $status = $helpSection->is_active ? 'activada' : 'desactivada';
        return redirect()->route('admin.help.index')->with('success', "Sección {$status} exitosamente.");
    }

    /**
     * Procesar y guardar imágenes
     */
    private function processImages(Request $request, HelpSection $helpSection)
    {
        $images = $helpSection->images ?? [];
        foreach ($request->file('images') as $file) {
            // Generar nombre único para el archivo
            $filename = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            // Leer contenido y guardarlo como base64 (data URI)
            $contents = file_get_contents($file->getRealPath());
            $base64 = base64_encode($contents);
            $dataUri = 'data:' . $file->getMimeType() . ';base64,' . $base64;

            // Generar referencia para la imagen
            $reference = $helpSection->generateImageReference($file->getClientOriginalName());

            // Agregar metadatos de la imagen (guardando el data URI en 'data')
            $images[] = [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'reference' => $reference,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now()->toISOString(),
                'data' => $dataUri,
            ];
        }
        
        $helpSection->update(['images' => $images]);
    }

    /**
     * Eliminar una imagen específica
     */
    public function deleteImage(HelpSection $helpSection, $imageIndex)
    {
        $images = $helpSection->images ?? [];
        
        if (isset($images[$imageIndex])) {
            // Remover de la lista (no hay archivo físico cuando usamos base64)
            unset($images[$imageIndex]);
            $images = array_values($images); // Reindexar array

            $helpSection->update(['images' => $images]);

            return response()->json(['success' => true, 'message' => 'Imagen eliminada exitosamente']);
        }
        
        return response()->json(['success' => false, 'message' => 'Imagen no encontrada'], 404);
    }

    /**
     * Subir imagen vía AJAX
     */
    public function uploadImage(Request $request, HelpSection $helpSection)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $file = $request->file('image');
        
        // Generar nombre único para el archivo
        $filename = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        // Leer contenido y convertir a base64
        $contents = file_get_contents($file->getRealPath());
        $base64 = base64_encode($contents);
        $dataUri = 'data:' . $file->getMimeType() . ';base64,' . $base64;

        // Generar referencia para la imagen
        $reference = $helpSection->generateImageReference($file->getClientOriginalName());

        // Agregar metadatos de la imagen
        $images = $helpSection->images ?? [];
        $imageData = [
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'reference' => $reference,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_at' => now()->toISOString(),
            'data' => $dataUri,
        ];

        $images[] = $imageData;
        $helpSection->update(['images' => $images]);

        return response()->json([
            'success' => true,
            'image' => $imageData,
            'url' => $helpSection->getImageUrl($filename),
            'reference' => $reference,
            'index' => count($images) - 1
        ]);
    }

    /**
     * Mostrar una imagen almacenada del manual de ayuda.
     */
    public function showImage($filename)
    {
        // Buscar la imagen en las secciones de ayuda y devolverla si existe (cuando se almacenan como base64)
        $sections = HelpSection::all();
        foreach ($sections as $section) {
            $images = $section->images ?? [];
            foreach ($images as $image) {
                if (isset($image['filename']) && $image['filename'] === $filename) {
                    if (isset($image['data'])) {
                        // data URI -> extraer mime y base64
                        if (preg_match('/^data:(.*);base64,(.*)$/', $image['data'], $matches)) {
                            $mime = $matches[1];
                            $b64 = $matches[2];
                            $content = base64_decode($b64);
                            return response($content, 200)
                                ->header('Content-Type', $mime)
                                ->header('Cache-Control', 'public, max-age=31536000');
                        }
                    }
                }
            }
        }

        abort(404);
    }
}
