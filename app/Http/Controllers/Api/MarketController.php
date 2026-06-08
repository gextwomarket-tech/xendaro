<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\PriceService;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Instrument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function instruments(Request $request): JsonResponse
    {
        $query = Instrument::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('symbol', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $instruments = $query->get();

        if ($request->user()) {
            $favoriteIds = $request->user()->favorites()->pluck('instrument_id')->toArray();
            $instruments->each(function ($inst) use ($favoriteIds) {
                $inst->is_favorite = in_array($inst->id, $favoriteIds);
            });
        }

        return ApiResponse::success($instruments);
    }

    public function chartData(Request $request, string $symbol, string $timeframe): JsonResponse
    {
        $instrument = Instrument::where('symbol', $symbol)->first();

        if (! $instrument) {
            return ApiResponse::error('Instrument introuvable', 404);
        }

        $count = min(500, match ($timeframe) {
            'M1' => 60, 'M5' => 100, 'M15' => 200, 'M30' => 200,
            'H1' => 200, 'H4' => 100, 'D1' => 100, 'W1' => 52,
            default => 100,
        });

        $dbCandles = $instrument->candles()
            ->where('timeframe', $timeframe)
            ->latest('time')
            ->limit($count)
            ->get()
            ->sortBy('time')
            ->map(fn ($c) => [
                'time' => $c->time->timestamp,
                'open' => (float) $c->open,
                'high' => (float) $c->high,
                'low' => (float) $c->low,
                'close' => (float) $c->close,
                'volume' => (float) $c->volume,
            ])
            ->values();

        if ($dbCandles->count() > 10) {
            $candles = $dbCandles->toArray();
        } else {
            $candles = $this->generateCandles($symbol, $timeframe);
        }

        return ApiResponse::success([
            'instrument_id' => $instrument->id,
            'symbol' => $symbol,
            'timeframe' => $timeframe,
            'candles' => $candles,
        ]);
    }

    public function prices(Request $request): JsonResponse
    {
        $prices = PriceService::getAllPrices();
        return ApiResponse::success($prices);
    }

    public function toggleFavorite(Request $request, string $symbol): JsonResponse
    {
        $instrument = Instrument::where('symbol', $symbol)->first();

        if (! $instrument) {
            return ApiResponse::error('Instrument introuvable', 404);
        }

        $favorite = $request->user()->favorites()
            ->where('instrument_id', $instrument->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return ApiResponse::success(['is_favorite' => false], 'Retiré des favoris');
        }

        $request->user()->favorites()->create([
            'instrument_id' => $instrument->id,
        ]);

        return ApiResponse::success(['is_favorite' => true], 'Ajouté aux favoris');
    }

    private function generateCandles(string $symbol, string $timeframe): array
    {
        $basePrice = match ($symbol) {
            'EURUSD' => 1.0850,
            'GBPUSD' => 1.2650,
            'USDJPY' => 150.20,
            'BTCUSD' => 65000.00,
            'ETHUSD' => 3400.00,
            default => 100.00,
        };

        $intervalMinutes = match ($timeframe) {
            'M1' => 1,
            'M5' => 5,
            'M15' => 15,
            'M30' => 30,
            'H1' => 60,
            'H4' => 240,
            'D1' => 1440,
            'W1' => 10080,
            default => 60,
        };

        $count = min(500, match ($timeframe) {
            'M1' => 60,
            'M5' => 100,
            'M15' => 200,
            'M30' => 200,
            'H1' => 200,
            'H4' => 100,
            'D1' => 100,
            'W1' => 52,
            default => 100,
        });

        $candles = [];
        $price = $basePrice;
        $now = now();

        for ($i = $count; $i >= 0; $i--) {
            $time = $now->copy()->subMinutes($i * $intervalMinutes);
            $open = $price;
            $change = (random_int(-100, 100) / 10000) * $basePrice;
            $close = $open + $change;
            $high = max($open, $close) + abs(random_int(0, 50) / 10000) * $basePrice;
            $low = min($open, $close) - abs(random_int(0, 50) / 10000) * $basePrice;
            $volume = random_int(100, 10000);

            $candles[] = [
                'time' => $time->timestamp,
                'open' => round($open, 5),
                'high' => round($high, 5),
                'low' => round($low, 5),
                'close' => round($close, 5),
                'volume' => $volume,
            ];

            $price = $close;
        }

        return $candles;
    }
}
