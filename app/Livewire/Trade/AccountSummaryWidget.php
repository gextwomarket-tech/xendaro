<?php

namespace App\Livewire\Trade;

use App\Models\TradeHistory;
use App\Services\MarketPriceService;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Resume de compte compact type MT5 (xendaro-fox-plan.json > Page id 37 > fonctionnalite
 * "account_summary"): solde / equite / marge utilisee / marge libre / niveau de marge.
 *
 * equite = solde + somme(P&L flottant des positions ouvertes)
 * marge libre = equite - marge utilisee
 * niveau de marge = equite / marge utilisee * 100 (null si aucune marge utilisee, comme MT5)
 *
 * Voir App\Services\TradingService pour la decision de modelisation "le solde brut n'est
 * jamais decremente a l'ouverture" - c'est precisement ce composant qui recalcule a la volee
 * la vue "equity/marge" attendue par un trader, sans avoir touche au solde stocke.
 */
class AccountSummaryWidget extends Component
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
        // Ecoute suffisante pour forcer un re-render de render().
    }

    #[On('trade-closed')]
    public function onTradeClosed(): void
    {
        // Ecoute suffisante pour forcer un re-render de render().
    }

    public function render()
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        $solde = $wallet ? $wallet->soldePour($this->modeActif) : 0.0;

        $positionsOuvertes = TradeHistory::query()
            ->where('user_id', $user->id)
            ->where('mode', $this->modeActif)
            ->where('statut', 'ouvert')
            ->with('instrument')
            ->get();

        $pnlFlottant = $positionsOuvertes->sum(function (TradeHistory $trade) {
            if (! $trade->instrument) {
                return 0.0;
            }

            return $trade->calculerProfitFlottant(MarketPriceService::currentPrice($trade->instrument));
        });

        $margeUtilisee = (float) $positionsOuvertes->sum('marge_utilisee');
        $equite = $solde + $pnlFlottant;
        $margeLibre = $equite - $margeUtilisee;
        $niveauMarge = $margeUtilisee > 0 ? round(($equite / $margeUtilisee) * 100, 2) : null;

        return view('livewire.trade.account-summary-widget', [
            'solde' => $solde,
            'equite' => $equite,
            'margeUtilisee' => $margeUtilisee,
            'margeLibre' => $margeLibre,
            'niveauMarge' => $niveauMarge,
            'pnlFlottant' => $pnlFlottant,
        ]);
    }
}
