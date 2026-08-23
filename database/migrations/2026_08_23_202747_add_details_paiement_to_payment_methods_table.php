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
        Schema::table('payment_methods', function (Blueprint $table) {
            // Detail concret a afficher au client pour effectuer son depot manuel
            // (adresse crypto, email PayPal, identifiant Perfect Money, IBAN...),
            // distinct du champ 'instructions' (texte explicatif general).
            $table->text('details_paiement')->nullable()->after('instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('details_paiement');
        });
    }
};
