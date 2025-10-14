<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Crear usuario administrador
        User::factory()->create([
            'name' => 'Administrador TI',
            'email' => 'sistemas@estrategiaeinnovacion.com.mx',
            'password' => bcrypt('Estrategias2025IT'),
        ]);
    }
}
