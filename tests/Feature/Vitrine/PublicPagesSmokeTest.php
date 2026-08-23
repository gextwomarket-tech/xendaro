<?php

namespace Tests\Feature\Vitrine;

use App\Models\EducationResource;
use App\Models\MarketInstrument;
use App\Models\NewsArticle;
use App\Models\SiteIdentifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test de regression pour le perimetre Public/Vitrine (Pages id 1 a 24 de xendaro-fox-plan.json).
 * Comble le point releve dans docummentations.md (2026-08-23, bilan de session): le sous-agent
 * Vitrine avait verifie manuellement les 24 routes mais n'avait laisse aucun test automatise
 * pour verrouiller la non-regression apres la refonte visuelle (hero anime, x-reveal, mini-chart...).
 */
class PublicPagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Toutes les pages vitrine 100% statiques (Route::view, sans dependance a un enregistrement precis).
     */
    public function test_static_vitrine_pages_return_200(): void
    {
        SiteIdentifier::create(['nom_plateforme' => 'Xendaro Fox']);

        $routes = [
            'home',
            'our-services',
            'account-types',
            'platforms',
            'trading-conditions',
            'about',
            'why-us',
            'markets',
            'promotions',
            'affiliate-program',
            'education',
            'market-news',
            'economic-calendar',
            'trading-tools',
            'faq',
            'contact',
            'cgv',
            'policies',
            'cookies',
            'risk-disclosure',
            'aml-policy',
        ];

        foreach ($routes as $routeName) {
            $response = $this->get(route($routeName));
            $response->assertStatus(200, "Route [$routeName] did not return 200.");
        }
    }

    public function test_market_detail_page_returns_200_for_a_real_instrument(): void
    {
        SiteIdentifier::create(['nom_plateforme' => 'Xendaro Fox']);

        $instrument = MarketInstrument::create([
            'nom' => 'Euro / Dollar US',
            'symbole_interne' => 'EURUSD',
            'categorie' => 'forex',
            'symbole_provider_externe' => 'FX:EURUSD',
            'provider' => 'tradingview',
            'spread' => 0.0001,
            'levier_max' => 500,
            'prix_reference' => 1.085,
            'est_actif' => true,
        ]);

        $response = $this->get(route('market-detail', $instrument));

        $response->assertStatus(200);
    }

    public function test_education_article_page_returns_200_for_a_real_slug(): void
    {
        SiteIdentifier::create(['nom_plateforme' => 'Xendaro Fox']);

        $resource = EducationResource::create([
            'titre_fr' => 'Introduction au Forex',
            'slug' => 'introduction-au-forex',
            'contenu_fr' => 'Contenu de test.',
            'type' => 'cours',
            'est_actif' => true,
        ]);

        $response = $this->get(route('education-article', $resource));

        $response->assertStatus(200);
    }

    public function test_news_detail_page_returns_200_for_a_real_slug(): void
    {
        SiteIdentifier::create(['nom_plateforme' => 'Xendaro Fox']);

        $article = NewsArticle::create([
            'titre_fr' => 'Le marche du Forex cette semaine',
            'slug' => 'marche-forex-semaine',
            'contenu_fr' => 'Contenu de test.',
            'publie_le' => now(),
        ]);

        $response = $this->get(route('news-detail', $article));

        $response->assertStatus(200);
    }
}
