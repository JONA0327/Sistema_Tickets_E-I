<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProductionAdminSeeder extends Seeder
{
    /**
     * Run the database seeder para producción.
     */
    public function run(): void
    {
        // Verificar si ya existe un usuario administrador
        $adminExists = User::where('role', 'admin')->first();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Administrador TI',
                'email' => 'sistemas@estrategiaeinnovacion.com.mx',
                'password' => Hash::make('Estrategias2025IT'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Usuario administrador de producción creado exitosamente.');
            $this->command->info('Email: sistemas@estrategiaeinnovacion.com.mx');
            $this->command->warn('Contraseña configurada según especificaciones.');
        } else {
            $this->command->info('Ya existe un usuario administrador en el sistema.');
            $this->command->table(
                ['ID', 'Nombre', 'Email', 'Rol'],
                [[$adminExists->id, $adminExists->name, $adminExists->email, $adminExists->role]]
            );
        }
    }
}