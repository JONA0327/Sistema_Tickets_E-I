<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'codigo_producto' => 'MOUSE-TZ-2024',
                'identificador' => 'MOU-001',
                'nombre' => 'Mouse Techzone',
                'categoria' => 'Mouse',
                'marca' => 'Techzone',
                'modelo' => 'TZ-845',
                'numero_serie' => 'TZM845-001',
                'estado' => InventoryItem::ESTADO_DISPONIBLE,
                'es_funcional' => true,
                'ubicacion' => 'Almacén principal',
                'descripcion_general' => 'Mouse inalámbrico recargable con 1600 DPI y conectividad USB.',
                'notas' => 'Incluye cable de carga USB-A.',
            ],
            [
                'codigo_producto' => 'MOUSE-TZ-2024',
                'identificador' => 'MOU-002',
                'nombre' => 'Mouse Techzone',
                'categoria' => 'Mouse',
                'marca' => 'Techzone',
                'modelo' => 'TZ-845',
                'numero_serie' => 'TZM845-002',
                'estado' => InventoryItem::ESTADO_DANADO,
                'es_funcional' => false,
                'ubicacion' => 'Mesa de reparaciones',
                'descripcion_general' => 'Mouse inalámbrico recargable con 1600 DPI y conectividad USB.',
                'notas' => 'No funciona la rueda de scroll, cable con detalles visibles.',
            ],
            [
                'codigo_producto' => 'TECL-LOGI-920',
                'identificador' => 'TEC-010',
                'nombre' => 'Teclado Logitech K380',
                'categoria' => 'Teclado',
                'marca' => 'Logitech',
                'modelo' => 'K380',
                'numero_serie' => 'LOGK380-010',
                'estado' => InventoryItem::ESTADO_PRESTADO,
                'es_funcional' => true,
                'ubicacion' => 'Estación soporte #3',
                'descripcion_general' => 'Teclado Bluetooth multidispositivo compacto.',
                'notas' => 'Prestado a Ana Torres para estación de trabajo móvil.',
            ],
            [
                'codigo_producto' => 'TECL-LOGI-920',
                'identificador' => 'TEC-011',
                'nombre' => 'Teclado Logitech K380',
                'categoria' => 'Teclado',
                'marca' => 'Logitech',
                'modelo' => 'K380',
                'numero_serie' => 'LOGK380-011',
                'estado' => InventoryItem::ESTADO_DISPONIBLE,
                'es_funcional' => true,
                'ubicacion' => 'Almacén principal',
                'descripcion_general' => 'Teclado Bluetooth multidispositivo compacto.',
                'notas' => 'Incluye baterías nuevas instaladas en enero 2025.',
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::updateOrCreate(
                [
                    'codigo_producto' => $item['codigo_producto'],
                    'identificador' => $item['identificador'],
                ],
                $item,
            );
        }
    }
}
