<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('instrument_id')->constrained()->onDelete('cascade');
            $table->string('type', 20); // MARKET, LIMIT, STOP
            $table->string('direction', 4); // BUY or SELL
            $table->decimal('volume', 18, 8);
            $table->decimal('price', 18, 8)->nullable(); // trigger price for limit/stop
            $table->decimal('stop_loss', 18, 8)->nullable();
            $table->decimal('take_profit', 18, 8)->nullable();
            $table->string('status', 20)->default('pending')->index(); // pending, executed, cancelled, expired
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
