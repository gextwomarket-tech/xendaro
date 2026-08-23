/*
 * Wrapper JS pour le widget "TradingView Advanced Chart" (script officiel public
 * https://s3.tradingview.com/tv.js) utilise sur la page Trade (xendaro-fox-plan.json > Page
 * id 37 "trade" > fonctionnalites graph / chart_toolbar).
 *
 * Ce module est charge en tant qu'entree Vite dediee (voir vite.config.js) et importe dans
 * le layout components/layouts/trade.blade.php. Il expose window.XendaroTradeChart, consomme
 * par le composant Livewire App\Livewire\Trade\ChartPanel (voir
 * resources/views/livewire/trade/chart-panel.blade.php) via un conteneur wire:ignore +
 * Alpine.js (le widget TradingView gere lui-meme son DOM, Livewire ne doit jamais le re-render).
 *
 * NB: x-trading-chart (composant Blade partage prevu par la page id 8 "market-detail", hors
 * scope de cet agent) n'existe pas encore au moment de l'implementation de /trade. Pour ne pas
 * bloquer et ne pas risquer un conflit de fichier avec l'agent en charge de la vitrine, le
 * rendu du widget est garde ici, auto-suffisant. Si x-trading-chart est cree plus tard avec une
 * API compatible, ce module pourra etre reutilise tel quel derriere ce composant partage.
 */

let widgetInstance = null;
let widgetReady = false;
let pendingSymbol = null;

/**
 * Charge dynamiquement le script officiel TradingView (une seule fois par page), puis
 * appelle le callback une fois window.TradingView disponible.
 */
function loadTradingViewScript(callback) {
    if (window.TradingView) {
        callback();
        return;
    }

    const existing = document.getElementById('tradingview-widget-script');
    if (existing) {
        existing.addEventListener('load', callback);
        return;
    }

    const script = document.createElement('script');
    script.id = 'tradingview-widget-script';
    script.src = 'https://s3.tradingview.com/tv.js';
    script.async = true;
    script.onload = callback;
    script.onerror = () => {
        console.warn('[XendaroTradeChart] Impossible de charger le widget TradingView (reseau indisponible ?)');
    };
    document.head.appendChild(script);
}

/**
 * Monte le widget dans le conteneur DOM fourni.
 * @param {HTMLElement} container - element portant un id unique (wire:ignore).
 * @param {{symbol?: string, interval?: string, style?: string|number, locale?: string}} options
 */
function mount(container, options = {}) {
    if (!container || !container.id) {
        console.warn('[XendaroTradeChart] mount() nécessite un conteneur avec un id.');
        return;
    }

    loadTradingViewScript(() => {
        widgetReady = false;

        widgetInstance = new window.TradingView.widget({
            symbol: options.symbol || 'FX:EURUSD',
            interval: options.interval || '60',
            container_id: container.id,
            autosize: true,
            theme: 'dark',
            style: options.style ?? '1',
            timezone: 'Etc/UTC',
            locale: options.locale || 'fr',
            toolbar_bg: '#12161f',
            hide_side_toolbar: false,
            withdateranges: true,
            allow_symbol_change: false,
            details: false,
            enable_publishing: false,
            studies: [
                'MASimple@tv-basicstudies',
                'RSI@tv-basicstudies',
                'MACD@tv-basicstudies',
                'BB@tv-basicstudies',
            ],
            overrides: {
                'paneProperties.background': '#0b0e14',
                'paneProperties.vertGridProperties.color': 'rgba(255,255,255,0.06)',
                'paneProperties.horzGridProperties.color': 'rgba(255,255,255,0.06)',
            },
        });

        widgetInstance.onChartReady(() => {
            widgetReady = true;
            if (pendingSymbol) {
                updateSymbol(pendingSymbol);
                pendingSymbol = null;
            }
        });
    });
}

/** Change le symbole affiche sans recharger toute la page (clic watchlist). */
function updateSymbol(symbol) {
    if (!symbol) {
        return;
    }

    if (widgetInstance && widgetReady) {
        try {
            widgetInstance.activeChart().setSymbol(symbol);
        } catch (e) {
            console.warn('[XendaroTradeChart] setSymbol a echoue', e);
        }
    } else {
        pendingSymbol = symbol;
    }
}

/** Change le timeframe (M1/M5/.../MN) via le dropdown de la toolbar. */
function updateInterval(interval) {
    if (widgetInstance && widgetReady) {
        try {
            widgetInstance.activeChart().setResolution(interval);
        } catch (e) {
            console.warn('[XendaroTradeChart] setResolution a echoue', e);
        }
    }
}

/** Change le type de graphique (bougies/ligne/barres) via le dropdown de la toolbar. */
function updateStyle(style) {
    if (widgetInstance && widgetReady) {
        try {
            widgetInstance.activeChart().setChartType(Number(style));
        } catch (e) {
            console.warn('[XendaroTradeChart] setChartType a echoue', e);
        }
    }
}

window.XendaroTradeChart = { mount, updateSymbol, updateInterval, updateStyle };
