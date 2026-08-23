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
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parrain_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('filleul_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('montant', 18, 2)->default(0);
            $table->enum('statut', ['en_attente', 'valide', 'refuse'])->default('en_attente');
            $table->timestamps();

            $table->index(['parrain_id', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
    }
};
