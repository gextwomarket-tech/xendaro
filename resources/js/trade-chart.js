import { createChart, CandlestickSeries, LineSeries, BarSeries, ColorType } from 'lightweight-charts';

/*
 * Graphique central de la page Trade (xendaro-fox-plan.json > Page id 37 > fonctionnalites
 * "graph" et "chart_toolbar"), rendu avec lightweight-charts (lib gratuite, open-source).
 *
 * Remplace l'implementation precedente basee sur le widget public TradingView (tv.js) : ce
 * script ne fournit que des embeds statiques et ne supporte pas les methodes utilisees ici
 * (onChartReady, activeChart(), setSymbol()...) - ces methodes appartiennent a la "Charting
 * Library" TradingView, un produit sous licence separee et auto-heberge. D'ou le crash
 * "onChartReady is not a function" en production. lightweight-charts fournit une vraie API
 * de mise a jour de donnees sans cette limitation.
 *
 * Les bougies viennent de MarketPriceService::history() (voir routes/trade.php, endpoint
 * /trade/chart-data/{instrument}) - prix simules en l'absence de flux de marche reel (MVP).
 *
 * Ce module est charge en tant qu'entree Vite dediee (voir vite.config.js) et importe dans
 * le layout components/layouts/trade.blade.php. Il expose window.XendaroTradeChart, consomme
 * par le composant Livewire App\Livewire\Trade\ChartPanel via un conteneur wire:ignore +
 * Alpine.js (Livewire ne doit jamais re-render ce DOM, le chart gere lui-meme son contenu).
 *
 * IMPORTANT (2 pieges vecus, tous les deux corriges ici) :
 *
 * 1. trade-page.blade.php rend DEUX instances de <livewire:trade.chart-panel> en parallele
 *    (variantes responsive "chart-desktop" et "chart-mobile", une seule visible a la fois via
 *    CSS) - donc DEUX conteneurs mount() simultanement. L'etat (chart/series/timer) est
 *    necessairement garde PAR CONTENEUR (WeakMap), jamais dans des variables de module
 *    partagees, sinon la deuxieme instance ecrase la premiere.
 *
 * 2. Le wire:ignore sur le conteneur empeche Livewire de re-render son contenu, mais PAS Alpine
 *    de re-executer x-init : n'importe quel wire:poll d'un composant frere sur la page (Watchlist
 *    3s, PriceTicker 2s, OpenPositions 3s, AccountSummaryWidget 3s - donc un commit Livewire en
 *    moyenne toutes les ~0.7s) redeclenche x-init sur ce conteneur. Sans garde, chaque
 *    redeclenchement recreait un chart + relancait un setInterval de poll, faisant exploser le
 *    nombre de requetes /trade/chart-data (constate en test : ~1.5 requete/seconde au lieu d'une
 *    toutes les 3s). mount() doit donc etre un no-op silencieux si une instance existe deja pour
 *    ce conteneur exact (meme reference DOM, garantie stable par wire:ignore) - les changements
 *    d'instrument/timeframe/style passent uniquement par updateSymbol/updateInterval/updateStyle,
 *    jamais par un nouvel appel a mount().
 */

const POLL_INTERVAL_MS = 3000;

/** @type {WeakMap<HTMLElement, {chart: import('lightweight-charts').IChartApi, series: any, pollTimer: number|null, instrumentId: number|string|null, interval: string, style: string}>} */
const instances = new WeakMap();

function seriesDefinitionFor(style) {
    if (style === '3') return LineSeries;
    if (style === '0') return BarSeries;

    return CandlestickSeries;
}

function seriesOptionsFor(style) {
    if (style === '3') {
        return { color: '#f5a623', lineWidth: 2 };
    }

    if (style === '0') {
        return { upColor: '#34d399', downColor: '#f16a6a' };
    }

    return {
        upColor: '#34d399',
        downColor: '#f16a6a',
        borderVisible: false,
        wickUpColor: '#34d399',
        wickDownColor: '#f16a6a',
    };
}

function toSeriesPoint(instance, candle) {
    return instance.style === '3'
        ? { time: candle.time, value: candle.close }
        : candle;
}

async function fetchCandles(instance) {
    if (!instance.instrumentId) {
        return [];
    }

    try {
        const response = await fetch(`/trade/chart-data/${instance.instrumentId}?interval=${encodeURIComponent(instance.interval)}`, {
            headers: { Accept: 'application/json' },
        });

        if (!response.ok) {
            return [];
        }

        const data = await response.json();

        return data.candles || [];
    } catch (e) {
        console.warn('[XendaroTradeChart] Echec du chargement des bougies', e);

        return [];
    }
}

function applySeriesType(instance) {
    if (instance.series) {
        instance.chart.removeSeries(instance.series);
    }

    instance.series = instance.chart.addSeries(seriesDefinitionFor(instance.style), seriesOptionsFor(instance.style));
}

async function renderHistory(instance) {
    if (!instance.series) {
        return;
    }

    const candles = await fetchCandles(instance);

    if (!candles.length) {
        return;
    }

    instance.series.setData(candles.map((c) => toSeriesPoint(instance, c)));
    instance.chart.timeScale().fitContent();
}

function stopPolling(instance) {
    if (instance.pollTimer) {
        clearInterval(instance.pollTimer);
        instance.pollTimer = null;
    }
}

function startPolling(instance) {
    stopPolling(instance);

    instance.pollTimer = setInterval(async () => {
        const candles = await fetchCandles(instance);

        if (!candles.length || !instance.series) {
            return;
        }

        instance.series.update(toSeriesPoint(instance, candles[candles.length - 1]));
    }, POLL_INTERVAL_MS);
}

/**
 * Monte le graphique dans le conteneur DOM fourni.
 * @param {HTMLElement} container
 * @param {{instrumentId?: number|string, interval?: string, style?: string}} options
 */
function mount(container, options = {}) {
    if (!container) {
        console.warn('[XendaroTradeChart] mount() nécessite un conteneur.');
        return;
    }

    // no-op si deja monte sur ce conteneur exact - voir note en tete de fichier (x-init peut
    // etre redeclenche par un wire:poll frere sans rapport ; les changements d'instrument/
    // timeframe/style passent par les fonctions update* dediees, jamais par un remount).
    if (instances.has(container)) {
        return;
    }

    const instance = {
        chart: null,
        series: null,
        pollTimer: null,
        instrumentId: options.instrumentId ?? null,
        interval: options.interval || '60',
        style: options.style ?? '1',
    };

    instance.chart = createChart(container, {
        autoSize: true,
        layout: {
            background: { type: ColorType.Solid, color: 'transparent' },
            textColor: '#8b93a7',
        },
        grid: {
            vertLines: { color: 'rgba(255,255,255,0.06)' },
            horzLines: { color: 'rgba(255,255,255,0.06)' },
        },
        rightPriceScale: { borderColor: 'rgba(255,255,255,0.08)' },
        timeScale: { borderColor: 'rgba(255,255,255,0.08)', timeVisible: true, secondsVisible: false },
    });

    instances.set(container, instance);

    applySeriesType(instance);
    renderHistory(instance);
    startPolling(instance);
}

/** Change l'instrument affiche (clic watchlist). */
function updateSymbol(container, instrumentId) {
    const instance = instances.get(container);

    if (!instance) {
        return;
    }

    instance.instrumentId = instrumentId;
    renderHistory(instance);
}

/** Change le timeframe (M1/M5/.../MN) via le dropdown de la toolbar. */
function updateInterval(container, interval) {
    const instance = instances.get(container);

    if (!instance) {
        return;
    }

    instance.interval = interval;
    renderHistory(instance);
}

/** Change le type de graphique (bougies/ligne/barres) via le dropdown de la toolbar. */
function updateStyle(container, style) {
    const instance = instances.get(container);

    if (!instance) {
        return;
    }

    instance.style = style;
    applySeriesType(instance);
    renderHistory(instance);
}

window.XendaroTradeChart = { mount, updateSymbol, updateInterval, updateStyle };
