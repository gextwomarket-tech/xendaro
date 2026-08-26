<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Sans User::canAccessPanel(), Filament v3 autorise par defaut TOUT utilisateur authentifie a
 * acceder a TOUT panel - n'importe quel client inscrit pouvait donc charger /admin avec son
 * propre compte. Voir migration add_is_admin_to_users_table.
 */
class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_client_normal_ne_peut_pas_acceder_au_panel_admin(): void
    {
        $client = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($client)->get('/admin');

        $response->assertForbidden();
    }

    public function test_un_utilisateur_is_admin_peut_acceder_au_panel_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
    }
}
