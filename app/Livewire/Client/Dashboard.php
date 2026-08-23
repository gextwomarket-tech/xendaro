<?php

namespace App\Livewire\Client;

use App\Models\TradeHistory;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Page id 31 "client-dashboard" - vue d'ensemble: stats cards, donnees recentes.
 */
#[Layout('components.layouts.dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $totalTrades = $user->tradeHistories()->count();
        $totalPnl = $user->tradeHistories()->where('statut', 'cloture')->sum('profit_perte');

        $recentTrades = $user->tradeHistories()
            ->with('instrument')
            ->latest('ouvert_le')
            ->limit(5)
            ->get();

        $recentTransactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.client.dashboard', [
            'wallet' => $wallet,
            'totalTrades' => $totalTrades,
            'totalPnl' => $totalPnl,
            'recentTrades' => $recentTrades,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
