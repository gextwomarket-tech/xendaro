<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 30)->unique();
            $table->string('name');
            $table->string('category', 30)->index(); // forex, crypto, indices, commodities
            $table->decimal('bid', 18, 8)->nullable();
            $table->decimal('ask', 18, 8)->nullable();
            $table->decimal('spread', 18, 8)->nullable();
            $table->decimal('change_24h', 18, 8)->nullable();
            $table->decimal('change_24h_percent', 8, 4)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->json('specs')->nullable(); // leverage, contract_size, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};
