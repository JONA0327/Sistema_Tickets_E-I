<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Verificar si ya existe un usuario administrador
        $adminExists = User::where('email', 'admin@soporteit.com')->first();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@soporteit.com',
                'password' => Hash::make('Admin123!'), // Cambiar por una contraseña segura
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Usuario administrador creado exitosamente.');
            $this->command->info('Email: admin@soporteit.com');
            $this->command->info('Contraseña: Admin123!');
            $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
        } else {
            $this->command->info('El usuario administrador ya existe.');
        }
    }
}