<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foxbots', function (Blueprint $table) {
            $table->id();
            $table->string('name_bot');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);

            // Performance horaire configurée par l'admin
            $table->decimal('percentage_win_hour', 8, 4)->default(2.50)
                  ->comment('% de gain par heure (ex: 2.50 = 2.5%/h)');
            $table->decimal('percentage_lost_hour', 8, 4)->default(0.50)
                  ->comment('% de perte par heure en cas de drawdown');

            // Paramètres de trading
            $table->decimal('win_rate', 5, 2)->default(75.00)
                  ->comment('Taux de réussite cible en % (ex: 75.00)');
            $table->decimal('risk_per_trade', 5, 2)->default(2.00)
                  ->comment('% du solde risqué par trade');
            $table->decimal('tp_multiplier', 5, 2)->default(2.00)
                  ->comment('Multiplicateur Take Profit vs Stop Loss');
            $table->unsignedTinyInteger('max_concurrent_positions')->default(3)
                  ->comment('Nombre max de positions ouvertes simultanément');

            // Timing (en secondes)
            $table->unsignedInteger('min_hold_seconds')->default(5);
            $table->unsignedInteger('max_hold_seconds')->default(25);

            // Stats globales (calculées automatiquement)
            $table->unsignedBigInteger('total_trades')->default(0);
            $table->unsignedBigInteger('total_wins')->default(0);
            $table->unsignedBigInteger('total_losses')->default(0);
            $table->decimal('total_pnl', 15, 2)->default(0);
            $table->decimal('total_pnl_demo', 15, 2)->default(0);

            // Affichage / marketing
            $table->string('avatar_emoji')->default('🤖')
                  ->comment('Emoji ou nom icône pour l\'affichage');
            $table->string('strategy_label')->nullable()
                  ->comment('Label affiché ex: "Scalping M1", "Swing H4"');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foxbots');
    }
};
