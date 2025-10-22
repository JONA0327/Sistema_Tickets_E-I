<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserNotificationsToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('user_has_updates')->default(false)->after('is_read');
            $table->timestamp('user_notified_at')->nullable()->after('user_has_updates');
            $table->timestamp('user_last_read_at')->nullable()->after('user_notified_at');
            $table->text('user_notification_summary')->nullable()->after('user_last_read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'user_has_updates',
                'user_notified_at',
                'user_last_read_at',
                'user_notification_summary',
            ]);
        });
    }
}
