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
        Schema::create('economic_events', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('devise', 10);
            $table->enum('importance', ['faible', 'moyenne', 'haute'])->default('moyenne');
            $table->dateTime('date_heure');
            $table->string('valeur_precedente')->nullable();
            $table->string('valeur_prevue')->nullable();
            $table->string('valeur_reelle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_events');
    }
};
