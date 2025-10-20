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
        Schema::table('inventarios', function (Blueprint $table) {
            // Campos específicos para discos duros con información
            $table->boolean('tiene_informacion')->default(false)->after('anos_uso');
            $table->text('informacion_contenido')->nullable()->after('tiene_informacion');
            $table->string('nivel_confidencialidad')->nullable()->after('informacion_contenido'); // bajo, medio, alto, critico
            $table->boolean('bloqueado_prestamo')->default(false)->after('nivel_confidencialidad');
            $table->text('razon_bloqueo')->nullable()->after('bloqueado_prestamo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropColumn([
                'tiene_informacion',
                'informacion_contenido', 
                'nivel_confidencialidad',
                'bloqueado_prestamo',
                'razon_bloqueo'
            ]);
        });
    }
};
