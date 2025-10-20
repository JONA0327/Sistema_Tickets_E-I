<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cambiar el enum para incluir 'dañado' con ñ
        DB::statement("ALTER TABLE inventarios MODIFY COLUMN estado ENUM('nuevo', 'usado', 'parcialmente_funcional', 'dañado')");
        
        // Actualizar registros existentes de 'danado' a 'dañado'
        DB::table('inventarios')->where('estado', 'danado')->update(['estado' => 'dañado']);
        
        echo "Estados actualizados en la tabla inventarios\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a 'danado' sin ñ
        DB::table('inventarios')->where('estado', 'dañado')->update(['estado' => 'danado']);
        DB::statement("ALTER TABLE inventarios MODIFY COLUMN estado ENUM('nuevo', 'usado', 'parcialmente_funcional', 'danado')");
    }
};
