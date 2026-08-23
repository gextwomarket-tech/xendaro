<?php

namespace App\Http\Controllers;

use App\Models\MarketInstrument;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pages publiques "Marches" (id 7) et "Detail d'un instrument" (id 8) de xendaro-fox-plan.json.
 * Prix/variation 24h simules pour le MVP (voir MarketPriceService, service partage avec la page Trade).
 */
class MarketController extends Controller
{
    public function index(Request $request): View
    {
        $categories = [
            'forex' => 'Forex',
            'crypto' => 'Crypto',
            'metal' => 'Or / Métaux',
            'commodite' => 'Matières premières',
            'indice' => 'Indices',
            'action' => 'Actions',
        ];

        $search = $request->query('search');
        $categorie = $request->query('categorie');

        $instruments = MarketInstrument::where('est_actif', true)
            ->when($search, fn ($q) => $q->where('nom', 'like', "%{$search}%")->orWhere('symbole_interne', 'like', "%{$search}%"))
            ->when($categorie, fn ($q) => $q->where('categorie', $categorie))
            ->orderBy('categorie')->orderBy('nom')
            ->paginate(12)
            ->withQueryString();

        return view('vitrine.markets', [
            'instruments' => $instruments,
            'categories' => $categories,
            'search' => $search,
            'categorie' => $categorie,
        ]);
    }

    public function show(MarketInstrument $instrument): View
    {
        $related = MarketInstrument::where('categorie', $instrument->categorie)
            ->where('id', '!=', $instrument->id)
            ->where('est_actif', true)
            ->limit(4)
            ->get();

        return view('vitrine.market-detail', [
            'instrument' => $instrument,
            'related' => $related,
        ]);
    }
}
