<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventario;
use App\Models\User;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'sistemas@estrategiaeinnovacion.com.mx')->first();
        
        if (!$admin) {
            $this->command->info('No se encontró usuario admin. Ejecutar UserSeeder primero.');
            return;
        }

        $inventarios = [
            [
                'categoria' => 'mouse',
                'articulo' => 'Mouse Óptico Logitech',
                'modelo' => 'M100',
                'cantidad' => 5,
                'estado' => 'nuevo',
                'observaciones' => 'Mouse básico para oficina',
                'created_by' => $admin->id
            ],
            [
                'categoria' => 'discos_duros',
                'articulo' => 'Disco Duro Externo Seagate',
                'modelo' => 'Backup Plus 1TB',
                'cantidad' => 3,
                'estado' => 'usado',
                'observaciones' => 'Para respaldos de datos',
                'created_by' => $admin->id
            ],
            [
                'categoria' => 'memorias_ram',
                'articulo' => 'Memoria RAM Kingston',
                'modelo' => 'DDR4 8GB 2666MHz',
                'cantidad' => 10,
                'estado' => 'nuevo',
                'observaciones' => 'Compatible con equipos Dell',
                'created_by' => $admin->id
            ],
            [
                'categoria' => 'cargadores',
                'articulo' => 'Cargador Universal Laptop',
                'modelo' => '90W Multi-tip',
                'cantidad' => 7,
                'estado' => 'usado',
                'observaciones' => 'Incluye múltiples conectores',
                'created_by' => $admin->id
            ],
            [
                'categoria' => 'baterias',
                'articulo' => 'Batería Laptop HP',
                'modelo' => 'HSTNN-LB6V',
                'cantidad' => 4,
                'estado' => 'parcialmente_funcional',
                'observaciones' => 'Duración reducida, funcional',
                'created_by' => $admin->id
            ],
            [
                'categoria' => 'computadoras',
                'articulo' => 'Laptop Dell Latitude',
                'modelo' => '5520',
                'cantidad' => 2,
                'estado' => 'usado',
                'observaciones' => 'Para trabajo en campo',
                'password_computadora' => 'Dell2024!',
                'anos_uso' => 2,
                'created_by' => $admin->id
            ],
            [
                'categoria' => 'otros',
                'articulo' => 'Hub USB 4 puertos',
                'modelo' => 'Anker PowerPort',
                'cantidad' => 6,
                'estado' => 'nuevo',
                'observaciones' => 'Para expansión de conectividad',
                'created_by' => $admin->id
            ]
        ];

        foreach ($inventarios as $data) {
            Inventario::create($data);
            $this->command->info('Creado: ' . $data['articulo'] . ' - Código generado automáticamente');
        }

        $this->command->info('¡Inventario de ejemplo creado exitosamente!');
    }
}
