<?php

namespace Database\Seeders;

use App\Models\EconomicEvent;
use Illuminate\Database\Seeder;

class EconomicEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            ['titre' => 'Décision de taux directeur - Fed', 'devise' => 'USD', 'importance' => 'haute', 'date_heure' => now()->addDays(1)->setTime(20, 0), 'valeur_precedente' => '5.50%', 'valeur_prevue' => '5.25%', 'valeur_reelle' => null],
            ['titre' => 'Indice des prix à la consommation (CPI)', 'devise' => 'USD', 'importance' => 'haute', 'date_heure' => now()->addDays(2)->setTime(14, 30), 'valeur_precedente' => '3.2%', 'valeur_prevue' => '3.0%', 'valeur_reelle' => null],
            ['titre' => 'Taux de chômage', 'devise' => 'EUR', 'importance' => 'moyenne', 'date_heure' => now()->addDays(2)->setTime(11, 0), 'valeur_precedente' => '6.5%', 'valeur_prevue' => '6.4%', 'valeur_reelle' => null],
            ['titre' => 'Décision de taux directeur - BCE', 'devise' => 'EUR', 'importance' => 'haute', 'date_heure' => now()->addDays(3)->setTime(14, 15), 'valeur_precedente' => '4.50%', 'valeur_prevue' => '4.50%', 'valeur_reelle' => null],
            ['titre' => 'PIB trimestriel', 'devise' => 'GBP', 'importance' => 'moyenne', 'date_heure' => now()->addDays(4)->setTime(8, 0), 'valeur_precedente' => '0.2%', 'valeur_prevue' => '0.3%', 'valeur_reelle' => null],
            ['titre' => 'Indice PMI manufacturier', 'devise' => 'USD', 'importance' => 'moyenne', 'date_heure' => now()->addDays(5)->setTime(15, 45), 'valeur_precedente' => '49.1', 'valeur_prevue' => '49.5', 'valeur_reelle' => null],
            ['titre' => 'Stocks de pétrole brut (EIA)', 'devise' => 'USD', 'importance' => 'faible', 'date_heure' => now()->addDays(1)->setTime(16, 30), 'valeur_precedente' => '-2.1M', 'valeur_prevue' => '-1.5M', 'valeur_reelle' => null],
            ['titre' => 'Ventes au détail', 'devise' => 'USD', 'importance' => 'moyenne', 'date_heure' => now()->subDays(1)->setTime(14, 30), 'valeur_precedente' => '0.4%', 'valeur_prevue' => '0.2%', 'valeur_reelle' => '0.3%'],
            ['titre' => 'Balance commerciale', 'devise' => 'JPY', 'importance' => 'faible', 'date_heure' => now()->subDays(2)->setTime(1, 50), 'valeur_precedente' => '-¥450B', 'valeur_prevue' => '-¥400B', 'valeur_reelle' => '-¥380B'],
            ['titre' => 'Indice de confiance des consommateurs', 'devise' => 'EUR', 'importance' => 'faible', 'date_heure' => now()->addDays(6)->setTime(16, 0), 'valeur_precedente' => '-15.5', 'valeur_prevue' => '-15.0', 'valeur_reelle' => null],
        ];

        foreach ($events as $event) {
            EconomicEvent::updateOrCreate(
                ['titre' => $event['titre'], 'date_heure' => $event['date_heure']],
                $event
            );
        }
    }
}
