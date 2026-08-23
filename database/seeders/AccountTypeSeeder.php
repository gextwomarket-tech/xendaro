<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nom' => 'Standard', 'depot_min' => 100, 'spread_min' => 0.00012, 'levier_max' => 200, 'swap_free' => false, 'description' => 'Le compte idéal pour débuter, spreads compétitifs et exécution fiable sur tous les instruments.', 'ordre' => 1],
            ['nom' => 'ECN', 'depot_min' => 500, 'spread_min' => 0.00002, 'levier_max' => 500, 'swap_free' => false, 'description' => 'Accès direct au marché interbancaire (ECN), spreads ultra-serrés dès 0.2 pip, commission par lot.', 'ordre' => 2],
            ['nom' => 'VIP', 'depot_min' => 5000, 'spread_min' => 0.00001, 'levier_max' => 500, 'swap_free' => false, 'description' => 'Conditions premium, gestionnaire de compte dédié, spreads les plus bas de la plateforme.', 'ordre' => 3],
            ['nom' => 'Islamique (sans swap)', 'depot_min' => 100, 'spread_min' => 0.00015, 'levier_max' => 200, 'swap_free' => true, 'description' => 'Compte conforme à la Charia, sans frais de swap sur les positions conservées la nuit.', 'ordre' => 4],
        ];

        foreach ($types as $type) {
            AccountType::updateOrCreate(['nom' => $type['nom']], $type + ['est_actif' => true]);
        }
    }
}
