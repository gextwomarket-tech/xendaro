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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['depot', 'retrait']);
            $table->decimal('montant', 18, 2);
            $table->enum('statut', ['en_attente', 'valide', 'refuse'])->default('en_attente');
            $table->string('reference')->unique();
            $table->text('note_admin')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
