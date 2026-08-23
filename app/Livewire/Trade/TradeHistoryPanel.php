<?php

namespace App\Livewire\Trade;

use App\Models\TradeHistory;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Sidebar gauche (rangee basse) de la page Trade: historique paginee des trades clotures du
 * compte actif (xendaro-fox-plan.json > Page id 37 > fonctionnalite "table" historique).
 * Reutilise <x-data-table> (voir resources/views/livewire/trade/trade-history-panel.blade.php).
 */
class TradeHistoryPanel extends Component
{
    use WithPagination;

    public string $modeActif = 'demo';

    public function mount(string $modeActif = 'demo'): void
    {
        $this->modeActif = $modeActif;
    }

    #[On('mode-changed')]
    public function onModeChanged(string $mode): void
    {
        $this->modeActif = $mode;
        $this->resetPage();
    }

    #[On('trade-closed')]
    public function refreshHistory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $trades = TradeHistory::query()
            ->where('user_id', auth()->id())
            ->where('mode', $this->modeActif)
            ->where('statut', 'cloture')
            ->with('instrument')
            ->latest('cloture_le')
            ->paginate(10);

        return view('livewire.trade.trade-history-panel', ['trades' => $trades]);
    }
}
