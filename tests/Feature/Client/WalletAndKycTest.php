<?php

namespace Tests\Feature\Client;

use App\Livewire\Client\DepositForm;
use App\Livewire\Client\WithdrawForm;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\WalletTransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Page id 36 "wallet" - depot/retrait creent une WalletTransaction 'en_attente',
 * le credit/debit reel du solde_reel n'intervient qu'a l'approbation admin
 * (voir app/Services/WalletTransactionService.php, action Filament Valider/Refuser).
 */
class WalletAndKycTest extends TestCase
{
    use RefreshDatabase;

    public function test_deposit_creates_pending_transaction_and_admin_approval_credits_wallet(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $method = PaymentMethod::create(['nom' => 'Virement bancaire', 'type' => 'virement', 'est_actif' => true]);

        Livewire::actingAs($user)
            ->test(DepositForm::class)
            ->set('payment_method_id', (string) $method->id)
            ->set('montant', '500')
            ->call('submit')
            ->assertHasNoErrors();

        $transaction = WalletTransaction::where('user_id', $user->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('en_attente', $transaction->statut);
        // 100 = bonus de bienvenue credite a l'inscription (voir User::booted()), le depot
        // en attente ne modifie pas encore le solde reel.
        $this->assertEquals(100, $user->fresh()->wallet->solde_reel);

        WalletTransactionService::approve($transaction);

        $this->assertEquals('valide', $transaction->fresh()->statut);
        $this->assertEquals(600, $user->fresh()->wallet->solde_reel);
    }

    public function test_withdraw_is_capped_to_available_real_balance(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->wallet()->update(['solde_reel' => 100]);
        $method = PaymentMethod::create(['nom' => 'Crypto', 'type' => 'crypto', 'est_actif' => true]);

        Livewire::actingAs($user)
            ->test(WithdrawForm::class)
            ->set('payment_method_id', (string) $method->id)
            ->set('montant', '150')
            ->call('submit')
            ->assertHasErrors('montant');

        $this->assertDatabaseCount('wallet_transactions', 0);
    }

    public function test_kyc_document_upload(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');

        $user = User::factory()->create(['email_verified_at' => now()]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Client\KycUploadForm::class)
            ->set('piece_identite', \Illuminate\Http\UploadedFile::fake()->create('id.pdf', 100, 'application/pdf'))
            ->call('uploadIdentite')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('kyc_documents', [
            'user_id' => $user->id,
            'type_document' => 'piece_identite',
            'statut' => 'en_attente',
        ]);
    }
}
