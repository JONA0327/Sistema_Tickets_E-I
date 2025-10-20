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
            $table->foreignId('maintenance_slot_id')->nullable()->constrained('maintenance_slots')->nullOnDelete();
            $table->dateTime('maintenance_scheduled_at')->nullable();
            $table->text('maintenance_details')->nullable();
            $table->string('equipment_identifier')->nullable();
            $table->string('equipment_brand')->nullable();
            $table->string('equipment_model')->nullable();
            $table->string('disk_type')->nullable();
            $table->string('ram_capacity')->nullable();
            $table->string('battery_status')->nullable();
            $table->text('aesthetic_observations')->nullable();
            $table->text('maintenance_report')->nullable();
            $table->text('closure_observations')->nullable();
            $table->json('replacement_components')->nullable();
            $table->foreignId('computer_profile_id')->nullable()->constrained('computer_profiles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('maintenance_slot_id');
            $table->dropConstrainedForeignId('computer_profile_id');
            $table->dropColumn([
                'maintenance_scheduled_at',
                'maintenance_details',
                'equipment_identifier',
                'equipment_brand',
                'equipment_model',
                'disk_type',
                'ram_capacity',
                'battery_status',
                'aesthetic_observations',
                'maintenance_report',
                'closure_observations',
                'replacement_components',
            ]);
        });
    }
};
