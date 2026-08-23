<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\RegisterForm;
use App\Livewire\Auth\VerifyEmailForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Verifie le parcours complet register -> verification email (OTP) -> dashboard,
 * puis logout -> login -> dashboard (voir xendaro-fox-plan.json Pages id 25, 26, 29, 31).
 * Couvre aussi la creation automatique du Wallet (event User::booted()).
 */
class RegisterLoginDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_register_verify_dashboard_flow(): void
    {
        Livewire::test(RegisterForm::class)
            ->set('name', 'Jean Dupont')
            ->set('email', 'jean.dupont@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('accept_terms', true)
            ->call('register')
            ->assertRedirect(route('verify-email'));

        $this->assertAuthenticated();

        /** @var User $user */
        $user = User::where('email', 'jean.dupont@example.com')->firstOrFail();

        // Wallet auto-cree via l'event User::booted() (solde_demo = 10000 par defaut).
        $this->assertNotNull($user->wallet);
        $this->assertEquals(10000, $user->wallet->solde_demo);
        $this->assertNotNull($user->otp_code);
        $this->assertNull($user->email_verified_at);

        Livewire::actingAs($user)
            ->test(VerifyEmailForm::class)
            ->set('code', $user->otp_code)
            ->call('verify')
            ->assertRedirect(route('client.dashboard'));

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->otp_code);

        $response = $this->actingAs($user)->get(route('client.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Jean Dupont');
    }

    public function test_login_redirects_verified_user_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'trader@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', 'trader@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('client.dashboard'));

        $this->assertAuthenticatedAs($user);

        $response = $this->actingAs($user)->get(route('client.dashboard'));
        $response->assertStatus(200);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'trader2@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', 'trader2@example.com')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }
}
