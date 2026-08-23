<?php

namespace App\Livewire\Client;

use App\Models\PaymentMethod;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Page id 36 "wallet" > popup_modal Depot. Cree une WalletTransaction en statut
 * 'en_attente' ; le credit du solde_reel n'intervient qu'a la validation admin
 * (voir WalletTransactionResource::approve dans app/Services/WalletTransactionService.php).
 */
class DepositForm extends Component
{
    public string $payment_method_id = '';

    public string $montant = '';

    protected function rules(): array
    {
        return [
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'montant' => ['required', 'numeric', 'min:10'],
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        WalletTransaction::create([
            'user_id' => Auth::id(),
            'payment_method_id' => $validated['payment_method_id'],
            'type' => 'depot',
            'montant' => $validated['montant'],
            'statut' => 'en_attente',
        ]);

        $this->reset(['payment_method_id', 'montant']);
        $this->dispatch('toast', type: 'success', message: __('app.client.wallet.deposit_success'));
        $this->dispatch('close-modal', name: 'deposit');
        $this->dispatch('wallet-transaction-created');
    }

    public function render()
    {
        return view('livewire.client.deposit-form', [
            'paymentMethods' => PaymentMethod::where('est_actif', true)->get(),
            'selectedMethod' => $this->payment_method_id
                ? PaymentMethod::find($this->payment_method_id)
                : null,
        ]);
    }
}
