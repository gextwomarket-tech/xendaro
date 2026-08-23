@props(['symbol', 'height' => 160])
{{--
    Widget TradingView "Mini Symbol Overview" - version legere (sparkline + prix) adaptee a
    un affichage en grille de plusieurs instruments simultanement (contrairement a <x-trading-chart>
    qui embarque le widget "Advanced Chart" complet, trop lourd pour en afficher 10-15 a la fois).
--}}
@php $containerId = 'tv-mini-'.\Illuminate\Support\Str::random(8); @endphp
<div id="{{ $containerId }}" class="tradingview-widget-container" style="height: {{ $height }}px">
    <div class="tradingview-widget-container__widget" style="height: 100%; width: 100%"></div>
</div>
<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
{
    "symbol": "{{ $symbol }}",
    "width": "100%",
    "height": "{{ $height }}",
    "locale": "{{ app()->getLocale() === 'en' ? 'en' : 'fr' }}",
    "dateRange": "1M",
    "colorTheme": "dark",
    "isTransparent": true,
    "autosize": true,
    "largeChartUrl": ""
}
</script>
