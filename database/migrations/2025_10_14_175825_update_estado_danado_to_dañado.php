<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar registros de inventario
        \DB::table('inventarios')
            ->where('estado', 'danado')
            ->update(['estado' => 'dañado']);
            
        // Actualizar observaciones que contengan "Estado: Danado"
        \DB::table('inventarios')
            ->where('observaciones', 'like', '%Estado: Danado%')
            ->update([
                'observaciones' => \DB::raw("REPLACE(observaciones, 'Estado: Danado', 'Estado: Dañado')")
            ]);
            
        // Actualizar registros de préstamos si existen
        if (Schema::hasColumn('prestamos_inventario', 'estado_devolucion')) {
            \DB::table('prestamos_inventario')
                ->where('estado_devolucion', 'danado')
                ->update(['estado_devolucion' => 'dañado']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir registros de inventario
        \DB::table('inventarios')
            ->where('estado', 'dañado')
            ->update(['estado' => 'danado']);
            
        // Revertir observaciones
        \DB::table('inventarios')
            ->where('observaciones', 'like', '%Estado: Dañado%')
            ->update([
                'observaciones' => \DB::raw("REPLACE(observaciones, 'Estado: Dañado', 'Estado: Danado')")
            ]);
            
        // Revertir registros de préstamos
        if (Schema::hasColumn('prestamos_inventario', 'estado_devolucion')) {
            \DB::table('prestamos_inventario')
                ->where('estado_devolucion', 'dañado')
                ->update(['estado_devolucion' => 'danado']);
        }
    }
};
