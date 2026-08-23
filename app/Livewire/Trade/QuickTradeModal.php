<?php

namespace App\Livewire\Trade;

use App\Models\MarketInstrument;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Dialog de prise de trade rapide, type MT5 (SANS overlay assombrissant), declenche depuis la
 * watchlist (xendaro-fox-plan.json > Page id 37 > fonctionnalite "popup_modal"). Reutilise
 * resources/views/components/modal.blade.php avec :overlay="false" (deja prevu pour cet usage)
 * et injecte a l'interieur le meme composant Livewire OrderForm (variant="quick") que celui
 * affiche sous le graph - aucune duplication de formulaire.
 */
class QuickTradeModal extends Component
{
    public ?int $instrumentId = null;

    public string $modeActif = 'demo';

    public function mount(?int $instrumentId = null, string $modeActif = 'demo'): void
    {
        $this->instrumentId = $instrumentId;
        $this->modeActif = $modeActif;
    }

    #[On('symbol-selected')]
    public function onSymbolSelected(int $instrumentId): void
    {
        $this->instrumentId = $instrumentId;
    }

    #[On('mode-changed')]
    public function onModeChanged(string $mode): void
    {
        $this->modeActif = $mode;
    }

    public function render()
    {
        return view('livewire.trade.quick-trade-modal', [
            'instrument' => $this->instrumentId ? MarketInstrument::find($this->instrumentId) : null,
        ]);
    }
}
