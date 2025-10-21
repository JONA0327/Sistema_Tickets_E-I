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
            // Solo agregar si no existen ya
            if (!Schema::hasColumn('tickets', 'equipment_password')) {
                $table->string('equipment_password')->nullable()->after('equipment_model');
            }
            if (!Schema::hasColumn('tickets', 'imagenes_admin')) {
                $table->json('imagenes_admin')->nullable()->after('replacement_components');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'equipment_password')) {
                $table->dropColumn('equipment_password');
            }
            if (Schema::hasColumn('tickets', 'imagenes_admin')) {
                $table->dropColumn('imagenes_admin');
            }
        });
    }
};