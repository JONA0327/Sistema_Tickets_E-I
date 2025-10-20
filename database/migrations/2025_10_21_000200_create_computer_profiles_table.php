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
        Schema::create('computer_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('disk_type')->nullable();
            $table->string('ram_capacity')->nullable();
            $table->string('battery_status')->nullable();
            $table->text('aesthetic_observations')->nullable();
            $table->json('replacement_components')->nullable();
            $table->dateTime('last_maintenance_at')->nullable();
            $table->boolean('is_loaned')->default(false);
            $table->string('loaned_to_name')->nullable();
            $table->string('loaned_to_email')->nullable();
            $table->foreignId('last_ticket_id')->nullable()->constrained('tickets')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computer_profiles');
    }
};
