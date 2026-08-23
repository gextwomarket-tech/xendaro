<?php

namespace App\Livewire\Trade;

use App\Models\TradeHistory;
use App\Services\MarketPriceService;
use App\Services\TradingService;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Sidebar droite (rangee basse): positions ouvertes avec P&L flottant live et fermeture rapide,
 * comme MT5 (xendaro-fox-plan.json > Page id 37 > fonctionnalite "position_table").
 * Rafraichissement via wire:poll.3s (voir vue) - pas de Laravel Echo pour ce MVP.
 */
class OpenPositions extends Component
{
    public string $modeActif = 'demo';

    public function mount(string $modeActif = 'demo'): void
    {
        $this->modeActif = $modeActif;
    }

    #[On('mode-changed')]
    public function onModeChanged(string $mode): void
    {
        $this->modeActif = $mode;
    }

    #[On('trade-opened')]
    public function onTradeOpened(): void
    {
        // Rien a faire ici: la simple ecoute de l'evenement force un re-render de render().
    }

    /**
     * Cloture une position ouverte du compte actif et notifie le reste de la page (toast +
     * evenement 'trade-closed' ecoute par TradeHistoryPanel/AccountSummaryWidget).
     */
    public function closePosition(int $tradeId): void
    {
        $trade = TradeHistory::query()
            ->where('user_id', auth()->id())
            ->where('statut', 'ouvert')
            ->findOrFail($tradeId);

        TradingService::closePosition($trade);

        $this->dispatch('toast', type: 'success', message: __('app.trade.trade_closed'));
        $this->dispatch('trade-closed');
    }

    public function render()
    {
        $positions = TradeHistory::query()
            ->where('user_id', auth()->id())
            ->where('mode', $this->modeActif)
            ->where('statut', 'ouvert')
            ->with('instrument')
            ->latest('ouvert_le')
            ->get()
            ->map(function (TradeHistory $trade) {
                $prixActuel = $trade->instrument ? MarketPriceService::currentPrice($trade->instrument) : (float) $trade->prix_ouverture;
                $trade->setAttribute('prix_actuel_calcule', $prixActuel);
                $trade->setAttribute('pnl_flottant', $trade->calculerProfitFlottant($prixActuel));

                return $trade;
            });

        return view('livewire.trade.open-positions', ['positions' => $positions]);
    }
}
