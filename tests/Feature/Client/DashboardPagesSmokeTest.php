<?php

namespace Tests\Feature\Client;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifie que toutes les pages de l'Espace Client (Pages id 31 a 42, hors Trade)
 * repondent en 200 pour un utilisateur authentifie + email verifie, sans erreur 500.
 */
class DashboardPagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_client_pages_return_200(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        PaymentMethod::create(['nom' => 'Carte bancaire', 'type' => 'carte', 'est_actif' => true]);

        $routes = [
            'client.dashboard',
            'client.security-settings',
            'client.trade-history',
            'client.markets',
            'client.wallet',
            'client.kyc',
            'client.notifications',
            'client.support',
            'client.affiliate-dashboard',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));
            $response->assertStatus(200, "Route [$routeName] did not return 200.");
        }
    }
}
