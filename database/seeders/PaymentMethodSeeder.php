<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

/**
 * Seed des moyens de paiement (consommes par le Wallet, id 36 dans xendaro-fox-plan.json).
 * Aucune page client dediee: uniquement CRUD Filament + options select des popups Depot/Retrait.
 */
class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'nom' => 'Carte bancaire',
                'type' => 'carte',
                'instructions' => 'Paiement instantané par carte Visa/Mastercard.',
                'frais' => 0,
                'delai_traitement' => 'Instantané',
                'est_actif' => true,
            ],
            [
                'nom' => 'Virement bancaire',
                'type' => 'virement',
                'instructions' => 'Virement SEPA/SWIFT vers le compte bancaire Xendaro Fox.',
                'frais' => 0,
                'delai_traitement' => '1 à 3 jours ouvrés',
                'est_actif' => true,
            ],
            [
                'nom' => 'Crypto (USDT)',
                'type' => 'crypto',
                'instructions' => 'Dépôt/retrait en USDT (réseau TRC20/ERC20).',
                'frais' => 0,
                'delai_traitement' => '10 à 30 minutes',
                'est_actif' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(['nom' => $method['nom']], $method);
        }
    }
}
