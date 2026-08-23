<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\EducationResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EducationResourceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::ofType('education')->pluck('id', 'slug');

        $resources = [
            [
                'titre_fr' => 'Les bases du trading Forex',
                'titre_en' => 'Forex Trading Basics',
                'category_id' => $categories['debutant'] ?? null,
                'type' => 'cours',
                'contenu_fr' => "Le marché des changes (Forex) est le plus grand marché financier au monde. Dans ce cours, vous apprendrez ce qu'est une paire de devises, comment se lit une cotation, la différence entre position acheteuse et vendeuse, et les bases pour passer votre premier ordre. Nous verrons également le vocabulaire essentiel : pip, spread, lot et effet de levier.",
                'contenu_en' => "The foreign exchange market (Forex) is the largest financial market in the world. In this course you'll learn what a currency pair is, how to read a quote, the difference between a long and short position, and the basics to place your first order. We'll also cover the essential vocabulary: pip, spread, lot and leverage.",
            ],
            [
                'titre_fr' => 'Comprendre les chandeliers japonais',
                'titre_en' => 'Understanding Japanese Candlesticks',
                'category_id' => $categories['analyse-technique'] ?? null,
                'type' => 'cours',
                'contenu_fr' => "Les chandeliers japonais sont l'outil de lecture graphique le plus utilisé par les traders. Ce cours détaille l'anatomie d'un chandelier (ouverture, clôture, mèches), les figures classiques (marteau, étoile filante, doji) et comment les intégrer dans votre stratégie d'entrée en position.",
                'contenu_en' => "Japanese candlesticks are the most widely used charting tool among traders. This course covers candle anatomy (open, close, wicks), classic patterns (hammer, shooting star, doji) and how to integrate them into your entry strategy.",
            ],
            [
                'titre_fr' => 'Gérer son risque : la règle des 2%',
                'titre_en' => 'Managing Risk: The 2% Rule',
                'category_id' => $categories['gestion-du-risque'] ?? null,
                'type' => 'cours',
                'contenu_fr' => "Une gestion du risque rigoureuse est ce qui distingue les traders qui durent des autres. Découvrez la règle des 2% par trade, comment calculer votre taille de position en fonction de votre stop loss, et pourquoi le ratio risque/rendement est central dans toute stratégie.",
                'contenu_en' => "Rigorous risk management is what separates traders who last from the rest. Discover the 2% per-trade rule, how to size your position based on your stop loss, and why the risk/reward ratio is central to any strategy.",
            ],
            [
                'titre_fr' => 'Glossaire du trader',
                'titre_en' => "Trader's Glossary",
                'category_id' => $categories['debutant'] ?? null,
                'type' => 'glossaire',
                'contenu_fr' => "Pip : plus petite variation de prix d'une paire de devises. Spread : différence entre prix d'achat et de vente. Lot : unité standard de volume de trading (100 000 unités pour un lot standard). Levier : capacité à trader une position supérieure à son capital. Marge : capital immobilisé pour ouvrir une position.",
                'contenu_en' => "Pip: the smallest price move of a currency pair. Spread: the difference between the bid and ask price. Lot: standard trading volume unit (100,000 units for a standard lot). Leverage: the ability to trade a position larger than your capital. Margin: capital locked to open a position.",
            ],
            [
                'titre_fr' => 'Webinaire : Analyser le marché des cryptomonnaies',
                'titre_en' => 'Webinar: Analyzing the Crypto Market',
                'category_id' => $categories['webinaires'] ?? null,
                'type' => 'webinaire',
                'contenu_fr' => "Rejoignez nos experts pour une session dédiée à l'analyse du marché crypto : cycles de marché, corrélation avec le Bitcoin, et gestion de la volatilité propre aux cryptomonnaies.",
                'contenu_en' => "Join our experts for a session dedicated to crypto market analysis: market cycles, correlation with Bitcoin, and managing the volatility specific to cryptocurrencies.",
            ],
            [
                'titre_fr' => "L'effet de levier : opportunités et risques",
                'titre_en' => 'Leverage: Opportunities and Risks',
                'category_id' => $categories['gestion-du-risque'] ?? null,
                'type' => 'cours',
                'contenu_fr' => "L'effet de levier permet de démultiplier ses gains potentiels, mais aussi ses pertes. Ce cours explique comment il fonctionne concrètement, avec des exemples chiffrés, et les bonnes pratiques pour l'utiliser sans mettre en péril votre capital.",
                'contenu_en' => "Leverage can amplify your potential gains, but also your losses. This course explains how it works concretely, with worked examples, and best practices to use it without jeopardizing your capital.",
            ],
        ];

        foreach ($resources as $resource) {
            EducationResource::updateOrCreate(
                ['slug' => Str::slug($resource['titre_fr'])],
                $resource + ['slug' => Str::slug($resource['titre_fr']), 'est_actif' => true]
            );
        }
    }
}
