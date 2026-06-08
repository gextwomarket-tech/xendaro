<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type', 20)->index(); // deposit, withdraw
            $table->decimal('amount', 18, 8);
            $table->string('method', 50); // card, bank_transfer, crypto, mobile_money
            $table->string('status', 20)->default('pending')->index(); // pending, approved, rejected, processing
            $table->string('reference', 50)->unique()->nullable();
            $table->string('currency', 10)->default('USD');
            $table->json('details')->nullable(); // method-specific fields
            $table->string('proof_url')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
