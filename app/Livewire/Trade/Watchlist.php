<?php

namespace App\Livewire\Trade;

use App\Models\MarketInstrument;
use App\Services\MarketPriceService;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Sidebar gauche (rangee haute) de la page Trade: watchlist recherchable/filtrable des
 * instruments actifs, avec prix/variation "live" simules (App\Services\MarketPriceService).
 * Un clic sur une ligne charge l'instrument dans le graph/ticker/formulaire via l'evenement
 * Livewire global 'symbol-selected'; l'icone "trade rapide" ouvre en plus le dialog flottant
 * sans overlay (voir App\Livewire\Trade\QuickTradeModal).
 *
 * NB pagination: bonnes_pratiques_dev impose la pagination pour "tout tableau/liste avec des
 * rangees de donnees", mais l'instruction specifique de la fonctionnalite "sidebar - watchlist"
 * (Page id 37) ne demande qu'un search_input + select_filter, sans pagination - a la difference
 * du "table historique de trade" explicitement paginee. Avec ~15 instruments actifs au total et
 * un objectif de "surveillance en un coup d'oeil" propre a une watchlist de trading, une
 * pagination casserait l'usage (on doit voir toute sa liste surveillee simultanement). Ce choix
 * est documente ici et dans docummentations.md.
 */
class Watchlist extends Component
{
    public string $search = '';

    public string $categorieFilter = '';

    public ?int $activeInstrumentId = null;

    public string $modeActif = 'demo';

    public function mount(?int $activeInstrumentId = null, string $modeActif = 'demo'): void
    {
        $this->activeInstrumentId = $activeInstrumentId;
        $this->modeActif = $modeActif;
    }

    #[On('mode-changed')]
    public function onModeChanged(string $mode): void
    {
        $this->modeActif = $mode;
    }

    public function selectInstrument(int $instrumentId): void
    {
        $this->activeInstrumentId = $instrumentId;
        $this->dispatch('symbol-selected', instrumentId: $instrumentId);
    }

    public function openQuickTrade(int $instrumentId): void
    {
        $this->selectInstrument($instrumentId);
        $this->dispatch('open-modal', name: 'quick-trade');
    }

    public function render()
    {
        $instruments = MarketInstrument::query()
            ->where('est_actif', true)
            ->when($this->search !== '', function ($query) {
                $query->where(function ($sub) {
                    $sub->where('nom', 'like', "%{$this->search}%")
                        ->orWhere('symbole_interne', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categorieFilter !== '', fn ($query) => $query->where('categorie', $this->categorieFilter))
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->map(function (MarketInstrument $instrument) {
                $quote = MarketPriceService::bidAsk($instrument);
                $reference = (float) $instrument->prix_reference;
                $variation = $reference > 0 ? round((($quote['mid'] - $reference) / $reference) * 100, 2) : 0.0;

                $instrument->setAttribute('prix_actuel_calcule', $quote['mid']);
                $instrument->setAttribute('variation_calculee', $variation);

                return $instrument;
            });

        return view('livewire.trade.watchlist', ['instruments' => $instruments]);
    }
}
