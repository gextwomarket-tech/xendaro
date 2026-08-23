<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Page id 36 "wallet" - recharge libre du solde demo. Contrairement au depot reel
 * (WalletTransaction en 'en_attente' + validation admin), le solde_demo est fictif :
 * le credit est instantane, sans aucune validation, pour laisser le client tester
 * librement la plateforme (voir demande produit: "il doit pouvoir choisir a volonte
 * son solde demo").
 */
class TopUpDemoForm extends Component
{
    public string $montant = '';

    protected function rules(): array
    {
        return [
            'montant' => ['required', 'numeric', 'min:100', 'max:1000000'],
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $wallet = Auth::user()->wallet;
        $wallet->increment('solde_demo', $validated['montant']);

        $this->reset('montant');
        $this->dispatch('toast', type: 'success', message: __('app.client.wallet.topup_demo_success'));
        $this->dispatch('close-modal', name: 'topup-demo');
        $this->dispatch('wallet-transaction-created');
    }

    public function render()
    {
        return view('livewire.client.top-up-demo-form');
    }
}
