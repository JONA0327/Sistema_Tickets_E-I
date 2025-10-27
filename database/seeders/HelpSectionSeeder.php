<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HelpSection;

class HelpSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HelpSection::create([
            'title' => 'Cómo crear un ticket',
            'content' => 'Para crear un nuevo ticket en el sistema, sigue estos pasos:

1. **Accede al sistema** con tu usuario y contraseña
2. **Haz clic en "Crear Ticket"** en el menú principal
3. **Completa el formulario** con los siguientes datos:
   - Título descriptivo del problema
   - Descripción detallada del issue
   - Categoría (Hardware, Software, Red, etc.)
   - Prioridad (Baja, Media, Alta, Crítica)

4. **Adjunta imágenes** si es necesario para explicar mejor el problema
5. **Haz clic en "Enviar"** para crear el ticket

Una vez creado, recibirás un código de seguimiento que podrás usar para consultar el estado de tu ticket.',
            'section_order' => 1,
            'is_active' => true,
            'images' => [],
        ]);

        HelpSection::create([
            'title' => 'Cómo consultar el estado de un ticket',
            'content' => 'Para verificar el estado de tu ticket existente:

1. **Ve a la página principal** del sistema
2. **Busca la sección "Consultar Ticket"**
3. **Ingresa tu código de seguimiento** en el campo correspondiente
4. **Haz clic en "Buscar"**

El sistema te mostrará:
- Estado actual del ticket (Abierto, En Proceso, Resuelto, Cerrado)
- Comentarios del técnico
- Fecha de última actualización
- Tiempo estimado de resolución

Si no encuentras tu código de seguimiento, revisa el email que recibiste al crear el ticket.',
            'section_order' => 2,
            'is_active' => true,
            'images' => [],
        ]);

        HelpSection::create([
            'title' => 'Gestión de Inventario',
            'content' => 'El sistema incluye un módulo completo de gestión de inventario:

**Para Administradores:**
- Agregar nuevos equipos al inventario
- Actualizar información de equipos existentes
- Generar reportes de equipos
- Gestionar préstamos de equipos

**Información que se maneja:**
- Nombre y descripción del equipo
- Número de serie
- Estado (Disponible, En Uso, En Mantenimiento, Dañado)
- Ubicación actual
- Responsable asignado
- Fecha de compra y garantía

**Préstamos de Equipos:**
El sistema permite registrar préstamos temporales de equipos a empleados, manteniendo un historial completo de quién tiene qué equipo y cuándo debe devolverlo.',
            'section_order' => 3,
            'is_active' => true,
            'images' => [],
        ]);

        HelpSection::create([
            'title' => 'Soporte para GIFs y Archivos Grandes',
            'content' => 'El sistema ahora soporta la carga de archivos GIF animados para mejor documentación:

**Tipos de archivo soportados:**
- JPEG, JPG
- PNG
- GIF (incluyendo animados)
- WebP

**Límites de archivo:**
- Tamaño máximo: **100MB por archivo**
- Sin límite en el número de archivos por sección

**Uso recomendado de GIFs:**
- Tutoriales paso a paso
- Demostraciones de funcionalidades
- Explicaciones visuales de procesos
- Ejemplos de errores comunes

**Referencias de imágenes:**
Usa las referencias automáticas como [img:figura1] para insertar las imágenes en el texto. El sistema las convertirá automáticamente en las imágenes correspondientes.',
            'section_order' => 4,
            'is_active' => true,
            'images' => [],
        ]);
    }
}