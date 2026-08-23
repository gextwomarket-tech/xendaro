@props(['symbol' => null, 'height' => 480, 'interval' => 'D'])
{{--
    Wrapper Blade+JS embarquant le widget TradingView officiel (voir xendaro-fox-plan.json
    page id 8 "market-detail" > graph > x-trading-chart). Utilise le meme script officiel
    (s3.tradingview.com/tv.js) que la page Trade (resources/js/trade-chart.js), mais en embed
    autonome en lecture seule (pas de changement de symbole dynamique necessaire ici).
--}}
@php
    $containerId = 'tv-chart-'.\Illuminate\Support\Str::random(8);
@endphp
<div {{ $attributes->merge(['class' => 'rounded-sm border border-bordure-subtile bg-fond-card overflow-hidden']) }} style="height: {{ $height }}px">
    @if($symbol)
        <div id="{{ $containerId }}" class="w-full h-full"></div>
        <script>
            (function () {
                function renderChart() {
                    if (!window.TradingView) { return; }
                    new window.TradingView.widget({
                        symbol: @json($symbol),
                        interval: @json($interval),
                        container_id: @json($containerId),
                        autosize: true,
                        theme: 'dark',
                        style: '1',
                        timezone: 'Etc/UTC',
                        locale: document.documentElement.lang === 'en' ? 'en' : 'fr',
                        toolbar_bg: '#12161f',
                        hide_side_toolbar: true,
                        allow_symbol_change: false,
                        enable_publishing: false,
                        details: false,
                        overrides: {
                            'paneProperties.background': '#0b0e14',
                            'paneProperties.vertGridProperties.color': 'rgba(255,255,255,0.06)',
                            'paneProperties.horzGridProperties.color': 'rgba(255,255,255,0.06)',
                        },
                    });
                }

                if (window.TradingView) {
                    renderChart();
                    return;
                }

                var existing = document.getElementById('tradingview-widget-script');
                if (existing) {
                    existing.addEventListener('load', renderChart);
                    return;
                }

                var script = document.createElement('script');
                script.id = 'tradingview-widget-script';
                script.src = 'https://s3.tradingview.com/tv.js';
                script.async = true;
                script.onload = renderChart;
                document.head.appendChild(script);
            })();
        </script>
    @else
        <div class="w-full h-full flex items-center justify-center text-texte-secondaire text-sm">
            {{ __('app.market_detail.chart_unavailable') }}
        </div>
    @endif
</div>
