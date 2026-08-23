<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Categories generiques (colonne 'type') reutilisees par faq, education, market-news.
     */
    public function run(): void
    {
        $categories = [
            // FAQ
            ['type' => 'faq', 'nom_fr' => 'Compte & Inscription', 'nom_en' => 'Account & Registration', 'slug' => 'compte-inscription', 'ordre' => 1],
            ['type' => 'faq', 'nom_fr' => 'Dépôts & Retraits', 'nom_en' => 'Deposits & Withdrawals', 'slug' => 'depots-retraits', 'ordre' => 2],
            ['type' => 'faq', 'nom_fr' => 'Trading', 'nom_en' => 'Trading', 'slug' => 'trading', 'ordre' => 3],
            ['type' => 'faq', 'nom_fr' => 'Sécurité', 'nom_en' => 'Security', 'slug' => 'securite', 'ordre' => 4],
            // Education
            ['type' => 'education', 'nom_fr' => 'Débutant', 'nom_en' => 'Beginner', 'slug' => 'debutant', 'ordre' => 1],
            ['type' => 'education', 'nom_fr' => 'Analyse technique', 'nom_en' => 'Technical Analysis', 'slug' => 'analyse-technique', 'ordre' => 2],
            ['type' => 'education', 'nom_fr' => 'Gestion du risque', 'nom_en' => 'Risk Management', 'slug' => 'gestion-du-risque', 'ordre' => 3],
            ['type' => 'education', 'nom_fr' => 'Webinaires', 'nom_en' => 'Webinars', 'slug' => 'webinaires', 'ordre' => 4],
            // News
            ['type' => 'news', 'nom_fr' => 'Forex', 'nom_en' => 'Forex', 'slug' => 'forex', 'ordre' => 1],
            ['type' => 'news', 'nom_fr' => 'Crypto', 'nom_en' => 'Crypto', 'slug' => 'crypto', 'ordre' => 2],
            ['type' => 'news', 'nom_fr' => 'Matières premières', 'nom_en' => 'Commodities', 'slug' => 'matieres-premieres', 'ordre' => 3],
            ['type' => 'news', 'nom_fr' => 'Actions & Indices', 'nom_en' => 'Stocks & Indices', 'slug' => 'actions-indices', 'ordre' => 4],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['type' => $category['type'], 'slug' => $category['slug']],
                $category
            );
        }
    }
}
