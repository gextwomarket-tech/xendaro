<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notifications')) return;

        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('notifiable_id')->index();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });

        DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNull('user_id')
            ->update(['user_id' => DB::raw('notifiable_id')]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('notifications')) return;
        if (!Schema::hasColumn('notifications', 'user_id')) return;

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
