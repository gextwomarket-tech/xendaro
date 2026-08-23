<?php

namespace App\Livewire\Client;

use App\Models\PaymentMethod;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Page id 36 "wallet" > popup_modal Retrait. Montant plafonne au solde_reel disponible.
 * Le debit reel n'intervient qu'a la validation admin (WalletTransactionService::approve).
 */
class WithdrawForm extends Component
{
    public string $payment_method_id = '';

    public string $montant = '';

    protected function rules(): array
    {
        $wallet = Auth::user()->wallet;
        $max = $wallet ? (float) $wallet->solde_reel : 0;

        return [
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'montant' => ['required', 'numeric', 'min:10', 'max:'.max($max, 0)],
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        WalletTransaction::create([
            'user_id' => Auth::id(),
            'payment_method_id' => $validated['payment_method_id'],
            'type' => 'retrait',
            'montant' => $validated['montant'],
            'statut' => 'en_attente',
        ]);

        $this->reset(['payment_method_id', 'montant']);
        $this->dispatch('toast', type: 'success', message: __('app.client.wallet.withdraw_success'));
        $this->dispatch('close-modal', name: 'withdraw');
        $this->dispatch('wallet-transaction-created');
    }

    public function render()
    {
        return view('livewire.client.withdraw-form', [
            'paymentMethods' => PaymentMethod::where('est_actif', true)->get(),
            'availableBalance' => Auth::user()->wallet->solde_reel ?? 0,
        ]);
    }
}
