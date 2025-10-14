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
        Schema::create('problema_archivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->string('categoria');
            $table->string('titulo');
            $table->text('descripcion_problema');
            $table->text('solucion');
            $table->string('tipo_problema');
            $table->json('palabras_clave')->nullable();
            $table->enum('frecuencia', ['unico', 'ocasional', 'frecuente', 'critico'])->default('unico');
            $table->text('notas_adicionales')->nullable();
            $table->foreignId('archivado_por')->constrained('users')->onDelete('cascade');
            $table->timestamp('fecha_archivo')->useCurrent();
            $table->timestamps();
            
            $table->index(['categoria', 'tipo_problema']);
            $table->index('fecha_archivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problema_archivo');
    }
};
