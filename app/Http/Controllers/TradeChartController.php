<?php

namespace App\Http\Controllers;

use App\Models\MarketInstrument;
use App\Services\MarketPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Fournit l'historique de bougies (JSON) consomme par lightweight-charts sur la page Trade
 * (voir resources/js/trade-chart.js). Route protegee par le middleware 'auth' (routes/trade.php),
 * au meme titre que le reste de la page Trade.
 */
class TradeChartController extends Controller
{
    public function history(Request $request, MarketInstrument $instrument): JsonResponse
    {
        $interval = $request->query('interval', '60');

        return response()->json([
            'candles' => MarketPriceService::history($instrument, $interval),
        ]);
    }
}
