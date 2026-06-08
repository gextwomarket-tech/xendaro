<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Wallet : solde démo séparé ────────────────────────────
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('demo_balance', 18, 8)->default(10000)->after('trading_balance');
        });

        // ── Trades : type de compte + marge + clôture ─────────────
        Schema::table('trades', function (Blueprint $table) {
            $table->enum('account_type', ['demo', 'real'])->default('demo')->after('user_id');
            $table->decimal('margin', 18, 8)->default(0)->after('volume');
            $table->decimal('contract_size', 18, 8)->default(1)->after('margin');
            $table->string('close_reason', 50)->nullable()->after('duration_seconds');
            $table->boolean('is_bot')->default(false)->after('close_reason');
        });

        // ── Orders : type de compte ───────────────────────────────
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('account_type', ['demo', 'real'])->default('demo')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('demo_balance');
        });

        Schema::table('trades', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'margin', 'contract_size', 'close_reason', 'is_bot']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });
    }
};
