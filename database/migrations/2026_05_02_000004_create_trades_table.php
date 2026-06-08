<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('instrument_id')->constrained()->onDelete('cascade');
            $table->string('direction', 4); // BUY or SELL
            $table->decimal('volume', 18, 8);
            $table->decimal('entry_price', 18, 8);
            $table->decimal('exit_price', 18, 8)->nullable();
            $table->decimal('stop_loss', 18, 8)->nullable();
            $table->decimal('take_profit', 18, 8)->nullable();
            $table->decimal('profit_loss', 18, 8)->default(0);
            $table->decimal('profit_loss_pips', 18, 8)->nullable();
            $table->string('status', 20)->default('open')->index(); // open, closed
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
