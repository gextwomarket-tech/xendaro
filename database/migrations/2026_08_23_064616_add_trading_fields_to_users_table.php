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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar_path')->nullable()->after('phone');
            $table->string('referral_code', 12)->unique()->nullable()->after('avatar_path');
            $table->foreignId('parrain_id')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
            $table->string('otp_code', 6)->nullable()->after('parrain_id');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->boolean('two_factor_enabled')->default(false)->after('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parrain_id');
            $table->dropColumn(['phone', 'avatar_path', 'referral_code', 'otp_code', 'otp_expires_at', 'two_factor_enabled']);
        });
    }
};
