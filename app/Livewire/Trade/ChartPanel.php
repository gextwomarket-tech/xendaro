<?php

namespace App\Livewire\Trade;

use App\Models\MarketInstrument;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Graphique central (lightweight-charts) + toolbar timeframe/type de graphique
 * (xendaro-fox-plan.json > Page id 37 > fonctionnalites "graph" et "chart_toolbar" x2).
 *
 * Le graphique lui-meme est rendu dans un conteneur wire:ignore (voir
 * resources/views/livewire/trade/chart-panel.blade.php) et pilote par resources/js/trade-chart.js
 * (window.XendaroTradeChart) - Livewire ne doit jamais re-render ce DOM, seuls les changements de
 * symbole/timeframe/type sont pousses au widget via des evenements navigateur dedies
 * ('chart-symbol-changed', 'chart-interval-changed', 'chart-style-changed').
 *
 * Les bougies sont chargees cote JS via /trade/chart-data/{instrument} (voir
 * App\Http\Controllers\TradeChartController + App\Services\MarketPriceService::history()) -
 * prix simules en l'absence de flux de marche reel (MVP).
 */
class ChartPanel extends Component
{
    public ?int $instrumentId = null;

    /** Code d'intervalle: 1,5,15,30,60,240,D,W,M (voir mapping M1..MN du plan). */
    public string $interval = '60';

    /** Style: 1=Bougies, 3=Ligne, 0=Barres. */
    public string $chartType = '1';

    public function mount(?int $instrumentId = null): void
    {
        $this->instrumentId = $instrumentId;
    }

    #[On('symbol-selected')]
    public function onSymbolSelected(int $instrumentId): void
    {
        $this->instrumentId = $instrumentId;

        $this->dispatch('chart-symbol-changed', instrumentId: $instrumentId);
    }

    public function updatedInterval(string $value): void
    {
        $this->dispatch('chart-interval-changed', interval: $value);
    }

    public function updatedChartType(string $value): void
    {
        $this->dispatch('chart-style-changed', style: $value);
    }

    public function getInstrumentProperty(): ?MarketInstrument
    {
        return $this->instrumentId ? MarketInstrument::find($this->instrumentId) : null;
    }

    public function render()
    {
        return view('livewire.trade.chart-panel');
    }
}
