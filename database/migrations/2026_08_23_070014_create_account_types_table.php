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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('depot_min', 12, 2)->default(0);
            $table->decimal('spread_min', 8, 5)->default(0);
            $table->unsignedInteger('levier_max')->default(100);
            $table->boolean('swap_free')->default(false);
            $table->text('description')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
