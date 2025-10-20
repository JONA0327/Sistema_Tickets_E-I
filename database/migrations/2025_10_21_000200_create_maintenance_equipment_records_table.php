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
        Schema::create('maintenance_equipment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('usuario_nombre');
            $table->string('usuario_correo');
            $table->string('equipo_marca')->nullable();
            $table->string('equipo_modelo')->nullable();
            $table->string('equipo_tipo_disco')->nullable();
            $table->string('equipo_ram_capacidad')->nullable();
            $table->text('equipo_observaciones_esteticas')->nullable();
            $table->enum('equipo_bateria_estado', ['funcional', 'parcialmente_funcional', 'danada'])->nullable();
            $table->text('maintenance_cierre_observaciones')->nullable();
            $table->text('maintenance_reporte')->nullable();
            $table->json('maintenance_componentes_reemplazo')->nullable();
            $table->dateTime('mantenimiento_programado')->nullable();
            $table->boolean('prestado')->default(false);
            $table->string('prestado_a_nombre')->nullable();
            $table->string('prestado_a_correo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_equipment_records');
    }
};
