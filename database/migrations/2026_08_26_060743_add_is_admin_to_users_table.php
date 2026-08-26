<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sans cette colonne, App\Models\User n'implemente pas Filament\Models\Contracts\FilamentUser,
 * et Filament v3 autorise par defaut TOUT utilisateur authentifie a acceder a TOUT panel -
 * n'importe quel client inscrit (register) pouvait donc charger /admin avec son propre mot de
 * passe et acceder au back-office complet (utilisateurs, transactions, KYC...). Voir
 * App\Models\User::canAccessPanel().
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
