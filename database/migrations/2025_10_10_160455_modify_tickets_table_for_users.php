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
        Schema::table('tickets', function (Blueprint $table) {
            // Agregar columna user_id
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            // Eliminar columna codigo_seguridad si existe
            if (Schema::hasColumn('tickets', 'codigo_seguridad')) {
                $table->dropColumn('codigo_seguridad');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Revertir: agregar codigo_seguridad y eliminar user_id
            $table->string('codigo_seguridad')->nullable();
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
