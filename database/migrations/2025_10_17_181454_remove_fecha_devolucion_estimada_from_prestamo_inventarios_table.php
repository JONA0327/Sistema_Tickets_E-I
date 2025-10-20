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
        Schema::table('prestamos_inventario', function (Blueprint $table) {
            if (Schema::hasColumn('prestamos_inventario', 'fecha_devolucion_estimada')) {
                $table->dropColumn('fecha_devolucion_estimada');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestamos_inventario', function (Blueprint $table) {
            if (!Schema::hasColumn('prestamos_inventario', 'fecha_devolucion_estimada')) {
                $table->date('fecha_devolucion_estimada')->nullable()->after('fecha_prestamo');
            }
        });
    }
};
