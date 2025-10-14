<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario de prueba
        User::updateOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'Usuario Prueba',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Crear admin
        User::updateOrCreate(
            ['email' => 'sistemas@estrategiaeinnovacion.com.mx'],
            [
                'name' => 'Administrador TI',
                'password' => Hash::make('Estrategias2025TI'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
