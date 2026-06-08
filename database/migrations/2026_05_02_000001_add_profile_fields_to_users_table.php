<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('country');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'preferred_currency')) {
                $table->string('preferred_currency', 10)->default('USD')->after('address');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('preferred_currency');
            }
            if (!Schema::hasColumn('users', 'kyc_status')) {
                $table->string('kyc_status', 20)->default('pending')->after('avatar');
            }
            if (!Schema::hasColumn('users', 'kyc_level')) {
                $table->tinyInteger('kyc_level')->default(0)->after('kyc_status');
            }
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->string('two_factor_secret')->nullable()->after('kyc_level');
            }
            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('two_factor_secret');
            }
            if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->json('two_factor_recovery_codes')->nullable()->after('two_factor_enabled');
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 20)->unique()->nullable()->after('two_factor_recovery_codes');
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete()->after('referral_code');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('active')->after('referred_by');
            }
            if (!Schema::hasColumn('users', 'email_verification_token')) {
                $table->string('email_verification_token', 64)->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'email_verification_expires_at')) {
                $table->timestamp('email_verification_expires_at')->nullable()->after('email_verification_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'first_name', 'last_name', 'phone', 'date_of_birth', 'country', 'city', 'address',
                'preferred_currency', 'avatar', 'kyc_status', 'kyc_level',
                'two_factor_secret', 'two_factor_enabled', 'two_factor_recovery_codes',
                'referral_code', 'referred_by', 'status',
                'email_verification_token', 'email_verification_expires_at',
            ];
            $existing = array_filter($columns, fn($col) => Schema::hasColumn('users', $col));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
