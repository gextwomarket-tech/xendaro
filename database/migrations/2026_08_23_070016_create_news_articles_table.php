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
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('titre_fr');
            $table->string('titre_en')->nullable();
            $table->string('slug')->unique();
            $table->longText('contenu_fr');
            $table->longText('contenu_en')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('instrument_id')->nullable()->constrained('market_instruments')->nullOnDelete();
            $table->string('image')->nullable();
            $table->dateTime('publie_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
