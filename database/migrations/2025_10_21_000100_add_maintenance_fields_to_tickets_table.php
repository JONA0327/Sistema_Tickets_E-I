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
            $table->foreignId('maintenance_time_slot_id')->nullable()->constrained('maintenance_time_slots')->nullOnDelete();
            $table->date('maintenance_date')->nullable();
            $table->time('maintenance_time')->nullable();
            $table->text('maintenance_details')->nullable();
            $table->string('equipo_marca')->nullable();
            $table->string('equipo_modelo')->nullable();
            $table->string('equipo_tipo_disco')->nullable();
            $table->string('equipo_ram_capacidad')->nullable();
            $table->text('equipo_observaciones_esteticas')->nullable();
            $table->enum('equipo_bateria_estado', ['funcional', 'parcialmente_funcional', 'danada'])->nullable();
            $table->text('maintenance_cierre_observaciones')->nullable();
            $table->text('maintenance_reporte')->nullable();
            $table->json('maintenance_componentes_reemplazo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['maintenance_time_slot_id']);
            $table->dropColumn([
                'maintenance_time_slot_id',
                'maintenance_date',
                'maintenance_time',
                'maintenance_details',
                'equipo_marca',
                'equipo_modelo',
                'equipo_tipo_disco',
                'equipo_ram_capacidad',
                'equipo_observaciones_esteticas',
                'equipo_bateria_estado',
                'maintenance_cierre_observaciones',
                'maintenance_reporte',
                'maintenance_componentes_reemplazo',
            ]);
        });
    }
};
