<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('platform_settings')) return;
        if (Schema::hasColumn('platform_settings', 'bot_profit_rate_per_hour')) return;

        Schema::table('platform_settings', function (Blueprint $table) {
            $table->decimal('bot_profit_rate_per_hour', 5, 2)->default(2.50)->after('maintenance_mode');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('platform_settings')) return;
        if (!Schema::hasColumn('platform_settings', 'bot_profit_rate_per_hour')) return;

        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn('bot_profit_rate_per_hour');
        });
    }
};
