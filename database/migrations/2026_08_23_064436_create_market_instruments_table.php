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
        Schema::create('market_instruments', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('symbole_interne')->unique();
            $table->enum('categorie', ['forex', 'crypto', 'metal', 'commodite', 'indice', 'action']);
            $table->string('symbole_provider_externe')->nullable();
            $table->string('provider')->default('tradingview');
            $table->decimal('spread', 10, 5)->default(0);
            $table->unsignedInteger('levier_max')->default(100);
            $table->decimal('prix_reference', 18, 5)->default(0);
            $table->boolean('est_actif')->default(true);
            $table->string('icone')->nullable();
            $table->timestamps();

            $table->index(['categorie', 'est_actif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_instruments');
    }
};
