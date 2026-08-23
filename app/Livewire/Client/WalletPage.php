<?php

namespace App\Livewire\Client;

use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Page id 36 "wallet" - soldes + boutons Deposer/Retirer (popups) + historique paginé.
 */
#[Layout('components.layouts.dashboard')]
class WalletPage extends Component
{
    use WithPagination;

    protected $listeners = ['wallet-transaction-created' => '$refresh'];

    public function render()
    {
        $transactions = WalletTransaction::with('paymentMethod')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('livewire.client.wallet-page', [
            'wallet' => Auth::user()->wallet,
            'transactions' => $transactions,
        ]);
    }
}
