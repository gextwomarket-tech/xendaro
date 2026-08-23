<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

/**
 * Seed des moyens de paiement (consommes par le Wallet, id 36 dans xendaro-fox-plan.json).
 * Aucune page client dediee: uniquement CRUD Filament + options select des popups Depot/Retrait.
 * Tous en depot MANUEL (aucune passerelle automatisee branchee pour ce MVP) : le client envoie
 * les fonds vers 'details_paiement' (adresse crypto / email / identifiant compte), soumet sa
 * demande, qui reste en statut 'en_attente' jusqu'a validation d'un super admin (voir
 * App\Services\WalletTransactionService::approve).
 */
class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'nom' => 'Bitcoin (BTC)',
                'type' => 'crypto',
                'instructions' => 'Envoyez le montant exact en BTC à l\'adresse ci-dessous, puis soumettez votre demande. Le crédit est effectué après confirmation manuelle par notre équipe.',
                'details_paiement' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',
                'frais' => 0,
                'delai_traitement' => '30 à 60 minutes après confirmation réseau',
                'est_actif' => true,
            ],
            [
                'nom' => 'Ethereum (ETH)',
                'type' => 'crypto',
                'instructions' => 'Envoyez le montant exact en ETH (réseau Ethereum ERC20) à l\'adresse ci-dessous, puis soumettez votre demande.',
                'details_paiement' => '0x71C7656EC7ab88b098defB751B7401B5f6d8976',
                'frais' => 0,
                'delai_traitement' => '15 à 30 minutes après confirmation réseau',
                'est_actif' => true,
            ],
            [
                'nom' => 'USDT (TRC20)',
                'type' => 'crypto',
                'instructions' => 'Envoyez le montant exact en USDT sur le réseau TRON (TRC20 uniquement) à l\'adresse ci-dessous.',
                'details_paiement' => 'TXm1p9K3vFq7WnBz2eR8LcH4sYjD6tPqAx',
                'frais' => 0,
                'delai_traitement' => '10 à 20 minutes après confirmation réseau',
                'est_actif' => true,
            ],
            [
                'nom' => 'USDT (ERC20)',
                'type' => 'crypto',
                'instructions' => 'Envoyez le montant exact en USDT sur le réseau Ethereum (ERC20 uniquement) à l\'adresse ci-dessous.',
                'details_paiement' => '0x89D24A6b4CcB1B6fAA2625Fe562bDD9a23260359',
                'frais' => 0,
                'delai_traitement' => '15 à 30 minutes après confirmation réseau',
                'est_actif' => true,
            ],
            [
                'nom' => 'Litecoin (LTC)',
                'type' => 'crypto',
                'instructions' => 'Envoyez le montant exact en LTC à l\'adresse ci-dessous, puis soumettez votre demande.',
                'details_paiement' => 'ltc1qh6tf004ty7z7un2v5ntu4mkf630545gvhs45u7',
                'frais' => 0,
                'delai_traitement' => '10 à 20 minutes après confirmation réseau',
                'est_actif' => true,
            ],
            [
                'nom' => 'PayPal',
                'type' => 'e-wallet',
                'instructions' => 'Envoyez le montant en tant que "Paiement entre amis/famille" (Friends & Family) à l\'adresse ci-dessous. Indiquez votre email de compte Xendaro Fox en référence.',
                'details_paiement' => 'payments@xendarofox.com',
                'frais' => 0,
                'delai_traitement' => '1 à 3 heures ouvrées',
                'est_actif' => true,
            ],
            [
                'nom' => 'Perfect Money',
                'type' => 'e-wallet',
                'instructions' => 'Effectuez un transfert du montant exact vers le compte Perfect Money ci-dessous depuis votre propre compte Perfect Money.',
                'details_paiement' => 'U29384756',
                'frais' => 0,
                'delai_traitement' => '1 à 3 heures ouvrées',
                'est_actif' => true,
            ],
            [
                'nom' => 'Virement bancaire',
                'type' => 'virement',
                'instructions' => 'Effectuez un virement SEPA/SWIFT vers le compte bancaire Xendaro Fox ci-dessous en indiquant votre nom complet en référence.',
                'details_paiement' => "IBAN: FR76 3000 6000 0112 3456 7890 189\nBIC: AGRIFRPP\nBénéficiaire: Xendaro Fox SAS",
                'frais' => 0,
                'delai_traitement' => '1 à 3 jours ouvrés',
                'est_actif' => true,
            ],
        ];

        // Supprime les anciens moyens de paiement (ex: "Carte bancaire" instantanee, non
        // representative d'un MVP 100% manuel) pour repartir sur ce jeu coherent.
        PaymentMethod::whereNotIn('nom', array_column($methods, 'nom'))->delete();

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(['nom' => $method['nom']], $method);
        }
    }
}
