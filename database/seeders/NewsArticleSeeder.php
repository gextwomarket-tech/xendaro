<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MarketInstrument;
use App\Models\NewsArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::ofType('news')->pluck('id', 'slug');
        $eurusd = MarketInstrument::where('symbole_interne', 'EURUSD')->first();
        $btc = MarketInstrument::where('symbole_interne', 'BTCUSD')->first();
        $xau = MarketInstrument::where('symbole_interne', 'XAUUSD')->first();

        $articles = [
            [
                'titre_fr' => "L'EUR/USD sous pression avant les décisions des banques centrales",
                'titre_en' => 'EUR/USD Under Pressure Ahead of Central Bank Decisions',
                'category_id' => $categories['forex'] ?? null,
                'instrument_id' => $eurusd?->id,
                'contenu_fr' => "La paire EUR/USD évolue dans un range serré à l'approche des prochaines décisions de politique monétaire. Les investisseurs restent prudents, dans l'attente de signaux clairs sur la trajectoire des taux directeurs des deux côtés de l'Atlantique.",
                'contenu_en' => 'EUR/USD is trading in a tight range ahead of upcoming monetary policy decisions. Investors remain cautious, awaiting clear signals on the rate path on both sides of the Atlantic.',
                'publie_le' => now()->subDays(1),
            ],
            [
                'titre_fr' => 'Le Bitcoin retrouve de la vigueur après une phase de consolidation',
                'titre_en' => 'Bitcoin Regains Strength After a Consolidation Phase',
                'category_id' => $categories['crypto'] ?? null,
                'instrument_id' => $btc?->id,
                'contenu_fr' => "Après plusieurs semaines de consolidation, le Bitcoin affiche un regain d'intérêt de la part des investisseurs institutionnels. Les volumes échangés repartent à la hausse, un signal souvent annonciateur de mouvements directionnels.",
                'contenu_en' => 'After several weeks of consolidation, Bitcoin is showing renewed interest from institutional investors. Trading volumes are picking up again, a signal that often precedes directional moves.',
                'publie_le' => now()->subDays(2),
            ],
            [
                'titre_fr' => "L'or continue son ascension, valeur refuge privilégiée",
                'titre_en' => 'Gold Continues Its Rise as a Preferred Safe Haven',
                'category_id' => $categories['matieres-premieres'] ?? null,
                'instrument_id' => $xau?->id,
                'contenu_fr' => "Dans un contexte d'incertitude géopolitique persistante, l'or continue d'attirer les capitaux en quête de sécurité. Les analystes surveillent de près le niveau des taux réels, facteur clé de la valorisation du métal jaune.",
                'contenu_en' => "Amid persistent geopolitical uncertainty, gold continues to attract capital seeking safety. Analysts are closely watching real rate levels, a key driver of the yellow metal's valuation.",
                'publie_le' => now()->subDays(3),
            ],
            [
                'titre_fr' => 'Wall Street : les indices proches de leurs records',
                'titre_en' => 'Wall Street: Indices Near Record Highs',
                'category_id' => $categories['actions-indices'] ?? null,
                'instrument_id' => null,
                'contenu_fr' => "Les principaux indices américains évoluent proches de leurs plus hauts historiques, portés par des résultats d'entreprises supérieurs aux attentes et un discours toujours accommodant de la banque centrale.",
                'contenu_en' => 'Major US indices are trading near record highs, supported by better-than-expected corporate earnings and a still-accommodative central bank stance.',
                'publie_le' => now()->subDays(4),
            ],
            [
                'titre_fr' => 'Le pétrole recule sur fond de craintes de ralentissement de la demande',
                'titre_en' => 'Oil Falls Amid Demand Slowdown Concerns',
                'category_id' => $categories['matieres-premieres'] ?? null,
                'instrument_id' => null,
                'contenu_fr' => "Les cours du pétrole brut sont repartis à la baisse cette semaine, les investisseurs s'inquiétant d'un ralentissement de la demande mondiale malgré les efforts de réduction de l'offre par certains grands producteurs.",
                'contenu_en' => 'Crude oil prices fell again this week as investors worry about a slowdown in global demand despite supply-reduction efforts from some major producers.',
                'publie_le' => now()->subDays(5),
            ],
            [
                'titre_fr' => 'Ethereum : la mise à jour du réseau attendue par les investisseurs',
                'titre_en' => 'Ethereum: Investors Await the Network Upgrade',
                'category_id' => $categories['crypto'] ?? null,
                'instrument_id' => null,
                'contenu_fr' => "La communauté Ethereum attend avec impatience la prochaine mise à jour du réseau, qui devrait améliorer l'efficacité des transactions et réduire les frais pour les utilisateurs.",
                'contenu_en' => "The Ethereum community is eagerly awaiting the next network upgrade, expected to improve transaction efficiency and lower fees for users.",
                'publie_le' => now()->subDays(6),
            ],
        ];

        foreach ($articles as $article) {
            NewsArticle::updateOrCreate(
                ['slug' => Str::slug($article['titre_fr'])],
                $article + ['slug' => Str::slug($article['titre_fr'])]
            );
        }
    }
}
