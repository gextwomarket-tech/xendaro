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
        Schema::create('trade_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_instrument_id')->constrained()->cascadeOnDelete();
            $table->enum('mode', ['demo', 'reel'])->default('demo');
            $table->enum('sens', ['buy', 'sell']);
            $table->enum('type_ordre', ['marche', 'buy_limit', 'sell_limit', 'buy_stop', 'sell_stop'])->default('marche');
            $table->decimal('volume', 10, 2);
            $table->decimal('prix_ouverture', 18, 5);
            $table->decimal('prix_cloture', 18, 5)->nullable();
            $table->decimal('stop_loss', 18, 5)->nullable();
            $table->decimal('take_profit', 18, 5)->nullable();
            $table->decimal('marge_utilisee', 18, 2)->default(0);
            $table->decimal('profit_perte', 18, 2)->nullable();
            $table->enum('statut', ['ouvert', 'cloture'])->default('ouvert');
            $table->timestamp('ouvert_le')->useCurrent();
            $table->timestamp('cloture_le')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'statut']);
            $table->index(['user_id', 'mode', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_histories');
    }
};
