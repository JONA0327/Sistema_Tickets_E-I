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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique(); // Folio automático único
            $table->string('nombre_solicitante');
            $table->string('correo_solicitante');
            $table->string('nombre_programa')->nullable(); // Para software, puede ser nulo para hardware/mantenimiento
            $table->text('descripcion_problema');
            $table->json('imagenes')->nullable(); // Array de rutas de imágenes
            $table->enum('estado', ['abierto', 'en_proceso', 'cerrado'])->default('abierto');
            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('tipo_problema', ['software', 'hardware', 'mantenimiento']);
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
