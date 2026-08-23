@php
    $containerId = 'tv-chart-'.$this->getId();
@endphp
<div class="flex flex-col h-full min-h-0">
    {{-- Toolbar: dropdowns timeframe + type de graphique (xendaro-fox-plan.json > chart_toolbar) --}}
    <div class="flex items-center gap-2 px-3 py-2 border-b border-bordure-subtile shrink-0 bg-fond-surface/60">
        <span class="text-sm font-medium text-texte-principal truncate mr-auto">
            {{ $this->instrument->nom ?? __('app.trade.ticker.no_instrument') }}
        </span>

        <x-select-filter
            wire:model.live="interval"
            :options="[
                '1' => 'M1', '5' => 'M5', '15' => 'M15', '30' => 'M30',
                '60' => 'H1', '240' => 'H4', 'D' => 'D1', 'W' => 'W1', 'M' => 'MN',
            ]"
            class="text-xs"
        />

        <x-select-filter
            wire:model.live="chartType"
            :options="[
                '1' => __('app.trade.chart_types.candles'),
                '3' => __('app.trade.chart_types.line'),
                '0' => __('app.trade.chart_types.bars'),
            ]"
            class="text-xs"
        />
    </div>

    {{--
        Conteneur du widget TradingView - wire:ignore obligatoire: Livewire ne doit jamais
        re-render ce DOM (le widget gere lui-meme son contenu). Piloté par resources/js/trade-chart.js
        (window.XendaroTradeChart) via des evenements navigateur dedies.
    --}}
    <div
        class="flex-1 min-h-0 relative"
        wire:ignore
        x-data="{}"
        x-init="
            window.XendaroTradeChart && window.XendaroTradeChart.mount($el.querySelector('.tv-widget-container'), {
                symbol: @js($this->instrument?->symbole_provider_externe ?: 'FX:EURUSD'),
                interval: @js($interval),
                style: @js($chartType),
                locale: @js(app()->getLocale()),
            });
        "
        x-on:chart-symbol-changed.window="window.XendaroTradeChart && window.XendaroTradeChart.updateSymbol($event.detail.symbol)"
        x-on:chart-interval-changed.window="window.XendaroTradeChart && window.XendaroTradeChart.updateInterval($event.detail.interval)"
        x-on:chart-style-changed.window="window.XendaroTradeChart && window.XendaroTradeChart.updateStyle($event.detail.style)"
    >
        <div id="{{ $containerId }}" class="tv-widget-container absolute inset-0"></div>
    </div>
</div>
