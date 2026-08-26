<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\ForgotPasswordForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Un SMTP mal configure (ex. MAIL_HOST de dev laisse en prod) faisait planter cette page en
 * erreur 500 - Password::sendResetLink() (natif Laravel) n'etait pas couvert par le meme filet
 * que App\Services\OtpMailerService (voir docummentations.md). Ce crash creait aussi une fuite
 * d'enumeration de comptes : un email existant provoquait un 500 pendant qu'un email inexistant
 * affichait sereinement le message generique "envoye".
 */
class ForgotPasswordFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_sendResetLink_ne_plante_pas_si_lenvoi_mail_echoue(): void
    {
        Password::shouldReceive('sendResetLink')
            ->once()
            ->andThrow(new \RuntimeException('Connection refused'));

        Livewire::test(ForgotPasswordForm::class)
            ->set('email', 'quelquun@example.com')
            ->call('sendResetLink')
            ->assertHasNoErrors()
            ->assertSet('sent', true);
    }
}
