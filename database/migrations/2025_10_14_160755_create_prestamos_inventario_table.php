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
        Schema::create('prestamos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained('inventarios')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('cantidad_prestada');
            $table->timestamp('fecha_prestamo')->useCurrent();
            $table->timestamp('fecha_devolucion')->nullable();
            $table->text('observaciones_prestamo')->nullable();
            $table->text('observaciones_devolucion')->nullable();
            $table->enum('estado_prestamo', ['activo', 'devuelto', 'perdido', 'danado'])->default('activo');
            $table->foreignId('prestado_por')->constrained('users')->onDelete('cascade'); // Admin que presta
            $table->foreignId('recibido_por')->nullable()->constrained('users')->onDelete('set null'); // Admin que recibe
            $table->timestamps();
            
            $table->index(['user_id', 'estado_prestamo']);
            $table->index(['inventario_id', 'estado_prestamo']);
            $table->index('fecha_prestamo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos_inventario');
    }
};
