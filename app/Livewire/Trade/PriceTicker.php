<?php

namespace App\Livewire\Trade;

use App\Models\MarketInstrument;
use App\Services\MarketPriceService;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Ticker Bid/Ask/Spread live de l'instrument selectionne (xendaro-fox-plan.json > Page id 37 >
 * fonctionnalite "price_ticker"). Rafraichi via wire:poll (voir vue).
 *
 * "Flash vert/rouge au changement": plutot que de traquer l'etat cote client (Alpine) - ce qui
 * serait fragile a travers les morphs DOM de wire:poll - la direction (hausse/baisse/stable) est
 * calculee cote serveur en comparant au tick precedent ($previousMid, propriete Livewire donc
 * persistee entre deux requetes de la meme instance de composant), puis simplement traduite en
 * classe Tailwind text-succes/text-danger avec une transition CSS douce. Plus simple, plus fiable.
 */
class PriceTicker extends Component
{
    public ?int $instrumentId = null;

    public ?float $previousMid = null;

    public function mount(?int $instrumentId = null): void
    {
        $this->instrumentId = $instrumentId;
    }

    #[On('symbol-selected')]
    public function onSymbolSelected(int $instrumentId): void
    {
        if ($instrumentId !== $this->instrumentId) {
            $this->previousMid = null;
        }

        $this->instrumentId = $instrumentId;
    }

    public function render()
    {
        $instrument = $this->instrumentId ? MarketInstrument::find($this->instrumentId) : null;
        $quote = $instrument ? MarketPriceService::bidAsk($instrument) : null;

        $direction = 'flat';

        if ($quote && $this->previousMid !== null) {
            if ($quote['mid'] > $this->previousMid) {
                $direction = 'up';
            } elseif ($quote['mid'] < $this->previousMid) {
                $direction = 'down';
            }
        }

        if ($quote) {
            $this->previousMid = $quote['mid'];
        }

        return view('livewire.trade.price-ticker', [
            'instrument' => $instrument,
            'quote' => $quote,
            'direction' => $direction,
        ]);
    }
}
