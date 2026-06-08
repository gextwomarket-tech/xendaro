<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->onDelete('cascade');
            $table->string('timeframe', 10)->index(); // M1, M5, M15, M30, H1, H4, D1, W1
            $table->timestamp('time');
            $table->decimal('open', 18, 8);
            $table->decimal('high', 18, 8);
            $table->decimal('low', 18, 8);
            $table->decimal('close', 18, 8);
            $table->decimal('volume', 18, 8)->default(0);
            $table->timestamps();
            $table->unique(['instrument_id', 'timeframe', 'time']);
            $table->index(['instrument_id', 'timeframe', 'time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candles');
    }
};
