<?php

namespace App\Livewire\Trade;

use App\Models\MarketInstrument;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Graphique central (widget TradingView) + toolbar timeframe/type de graphique
 * (xendaro-fox-plan.json > Page id 37 > fonctionnalites "graph" et "chart_toolbar" x2).
 *
 * Le widget lui-meme est rendu dans un conteneur wire:ignore (voir
 * resources/views/livewire/trade/chart-panel.blade.php) et pilote par resources/js/trade-chart.js
 * (window.XendaroTradeChart) - Livewire ne doit jamais re-render ce DOM, seuls les changements de
 * symbole/timeframe/type sont pousses au widget via des evenements navigateur dedies
 * ('chart-symbol-changed', 'chart-interval-changed', 'chart-style-changed').
 *
 * Les indicateurs (MA/RSI/MACD/Bollinger) et les outils de dessin sont ceux nativement fournis
 * par le widget officiel TradingView (voir options 'studies' et 'hide_side_toolbar: false' dans
 * trade-chart.js) - aucun developpement custom necessaire pour le MVP, conformement a
 * l'instruction_suggestions_tache correspondante.
 */
class ChartPanel extends Component
{
    public ?int $instrumentId = null;

    /** Code d'intervalle TradingView: 1,5,15,30,60,240,D,W,M (voir mapping M1..MN du plan). */
    public string $interval = '60';

    /** Style TradingView: 1=Bougies, 3=Ligne, 0=Barres. */
    public string $chartType = '1';

    public function mount(?int $instrumentId = null): void
    {
        $this->instrumentId = $instrumentId;
    }

    #[On('symbol-selected')]
    public function onSymbolSelected(int $instrumentId): void
    {
        $this->instrumentId = $instrumentId;

        $instrument = MarketInstrument::find($instrumentId);

        if ($instrument && $instrument->symbole_provider_externe) {
            $this->dispatch('chart-symbol-changed', symbol: $instrument->symbole_provider_externe);
        }
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
