<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referee_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_commission', 18, 8)->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_id')->constrained()->onDelete('cascade');
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 18, 8);
            $table->string('status', 20)->default('pending')->index(); // pending, paid, cancelled
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
        Schema::dropIfExists('referrals');
    }
};
