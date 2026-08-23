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
        Schema::create('education_resources', function (Blueprint $table) {
            $table->id();
            $table->string('titre_fr');
            $table->string('titre_en')->nullable();
            $table->string('slug')->unique();
            $table->longText('contenu_fr');
            $table->longText('contenu_en')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('image')->nullable();
            $table->string('type')->default('cours');
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_resources');
    }
};
