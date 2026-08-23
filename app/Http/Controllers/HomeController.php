<?php

namespace App\Http\Controllers;

use App\Models\MarketInstrument;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Page d'accueil (Page id 1 de xendaro-fox-plan.json). Convertie de Route::view vers un
 * controller pour alimenter la section "Marches" (tableau + filtres) ajoutee sous les stats,
 * reprenant le meme pattern GET que MarketController::index (voir vitrine/markets.blade.php).
 */
class HomeController extends Controller
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
            ->take(8)
            ->get();

        return view('vitrine.home', [
            'marketInstruments' => $instruments,
            'marketCategories' => $categories,
            'marketSearch' => $search,
            'marketCategorie' => $categorie,
        ]);
    }
}
