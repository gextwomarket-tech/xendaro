<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->string('category', 50)->index(); // technical, financial, kyc, other
            $table->string('priority', 20)->default('medium')->index(); // low, medium, high, urgent
            $table->text('description');
            $table->string('status', 20)->default('open')->index(); // open, in_progress, closed, reopened
            $table->timestamp('last_replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
