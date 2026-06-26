<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Favorite;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function instruments()
    {
        $instruments = Instrument::where('is_active', true)->get();
        return response()->json($instruments);
    }

    public function prices()
    {
        $prices = Instrument::where('is_active', true)
            ->select('symbol', 'bid', 'ask', 'spread', 'change_24h', 'change_24h_percent')
            ->get();
        return response()->json($prices);
    }

    public function chartData($symbol, $timeframe)
    {
        // TODO: Fetch chart data from candles
        return response()->json([]);
    }

    public function showMarkets(Request $request)
    {
        $user = $request->user();

        $instruments = Instrument::where('is_active', true)
            ->orderBy('symbol')
            ->get();

        $favoriteIds = Favorite::where('user_id', $user->id)
            ->pluck('instrument_id')
            ->toArray();

        $categories = $instruments->groupBy('category')->keys()->sort()->values();

        return view('dashboard.markets', compact('instruments', 'favoriteIds', 'categories'));
    }

    public function toggleFavorite(Request $request, $symbol)
    {
        $user = $request->user();
        $instrument = Instrument::where('symbol', $symbol)->firstOrFail();

        $existing = Favorite::where('user_id', $user->id)
            ->where('instrument_id', $instrument->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Retiré des favoris.';
        } else {
            Favorite::create(['user_id' => $user->id, 'instrument_id' => $instrument->id]);
            $message = 'Ajouté aux favoris.';
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
