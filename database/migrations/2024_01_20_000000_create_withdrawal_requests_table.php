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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 15, 2);
            $table->decimal('fees', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->string('currency')->default('USD');
            $table->enum('payment_method', ['bank_transfer', 'cryptocurrency', 'card']);
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->string('reference')->unique();
            $table->text('rejection_reason')->nullable();

            // Bank Transfer Fields
            $table->string('bank_account_number')->nullable();
            $table->string('bank_swift')->nullable();
            $table->string('bank_bic')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->string('bank_name')->nullable();

            // Cryptocurrency Fields
            $table->string('crypto_type')->nullable();
            $table->string('crypto_address')->nullable();

            // Card Fields
            $table->string('card_number')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('card_bank_name')->nullable();
            $table->string('card_expiry')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
