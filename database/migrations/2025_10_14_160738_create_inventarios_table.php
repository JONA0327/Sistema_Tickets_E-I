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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->enum('categoria', ['mouse', 'discos_duros', 'memorias_ram', 'cargadores', 'baterias', 'computadoras', 'otros']);
            $table->string('articulo');
            $table->string('modelo');
            $table->integer('cantidad')->default(1);
            $table->enum('estado', ['nuevo', 'usado', 'parcialmente_funcional', 'danado']);
            $table->text('observaciones')->nullable();
            $table->json('imagenes')->nullable(); // Array de imágenes en base64
            
            // Campos específicos para computadoras
            $table->string('password_computadora')->nullable(); // Solo para computadoras
            $table->integer('anos_uso')->nullable(); // Solo para computadoras
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['categoria', 'estado']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
