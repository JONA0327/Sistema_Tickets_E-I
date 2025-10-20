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
        Schema::create('discos_en_uso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained('inventarios')->onDelete('cascade');
            $table->string('nombre_computadora'); // Nombre de la computadora donde está instalado
            $table->foreignId('computadora_inventario_id')->nullable()->constrained('inventarios')->onDelete('set null'); // ID si está registrada en inventario
            $table->text('razon_uso'); // Por qué se está usando este disco
            $table->string('disco_reemplazado')->nullable(); // Nombre/modelo del disco que reemplazó
            $table->text('detalles_reemplazo')->nullable(); // Detalles adicionales del reemplazo
            $table->date('fecha_instalacion'); // Cuándo se instaló
            $table->boolean('esta_activo')->default(true); // Si está actualmente en uso
            $table->date('fecha_retiro')->nullable(); // Cuándo se retiró (si aplica)
            $table->text('observaciones')->nullable(); // Observaciones adicionales
            $table->foreignId('instalado_por')->constrained('users')->onDelete('cascade'); // Quién lo instaló
            $table->foreignId('retirado_por')->nullable()->constrained('users')->onDelete('set null'); // Quién lo retiró
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discos_en_uso');
    }
};
