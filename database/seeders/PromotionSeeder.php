<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'titre' => 'Bonus de bienvenue 50%',
                'description' => 'Bénéficiez d\'un bonus de 50% sur votre premier dépôt, jusqu\'à 500$, pour démarrer votre aventure de trading avec plus de capital.',
                'date_debut' => now()->subDays(10)->toDateString(),
                'date_fin' => now()->addMonths(2)->toDateString(),
                'est_active' => true,
            ],
            [
                'titre' => 'Trading sans commission - 30 jours',
                'description' => 'Profitez de 30 jours sans commission sur tous vos trades ECN dès l\'ouverture de votre compte VIP.',
                'date_debut' => now()->subDays(5)->toDateString(),
                'date_fin' => now()->addMonth()->toDateString(),
                'est_active' => true,
            ],
            [
                'titre' => 'Programme de parrainage renforcé',
                'description' => 'Parrainez un ami et recevez jusqu\'à 100$ de commission supplémentaire sur son volume de trading du premier mois.',
                'date_debut' => now()->subDays(20)->toDateString(),
                'date_fin' => now()->addMonths(3)->toDateString(),
                'est_active' => true,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::updateOrCreate(['titre' => $promotion['titre']], $promotion);
        }
    }
}
