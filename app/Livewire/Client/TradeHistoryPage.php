<?php

namespace App\Livewire\Client;

use App\Models\MarketInstrument;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Page id 34 "trade-history" - historique complet et paginé des trades (demo + reel).
 */
#[Layout('components.layouts.dashboard')]
class TradeHistoryPage extends Component
{
    use WithPagination;

    public string $mode = '';

    public string $instrumentId = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    protected $queryString = ['mode', 'instrumentId', 'dateFrom', 'dateTo'];

    public function updating(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Auth::user()->tradeHistories()
            ->with('instrument')
            ->where('statut', 'cloture')
            ->when($this->mode, fn ($q) => $q->where('mode', $this->mode))
            ->when($this->instrumentId, fn ($q) => $q->where('market_instrument_id', $this->instrumentId))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('ouvert_le', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('ouvert_le', '<=', $this->dateTo))
            ->latest('ouvert_le');

        return view('livewire.client.trade-history-page', [
            'trades' => $query->paginate(15),
            'instruments' => MarketInstrument::where('est_actif', true)->orderBy('nom')->get(),
        ]);
    }
}
