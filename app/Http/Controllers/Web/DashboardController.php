<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $user->load([
            'wallet',
            'trades.instrument',
            'transactions',
        ]);

        $wallet = $user->wallet;

        // Trades ouverts
        $openTrades = $user->trades->where('status', 'open');
        $openTradesCount = $openTrades->count();
        $openTradesProfitable = $openTrades->where('profit_loss', '>', 0)->count();
        $openTradesPending = $openTradesCount - $openTradesProfitable;

        // Trades fermés — P&L réalisé
        $closedTrades = $user->trades->where('status', 'closed');
        $realisedPnl = $closedTrades->sum('profit_loss');

        // 5 derniers trades (ouverts ou fermés) pour l'affichage
        $recentTrades = $user->trades()
            ->with('instrument')
            ->latest('opened_at')
            ->take(5)
            ->get();

        // 5 derniers dépôts
        $recentDeposits = $user->transactions()
            ->where('type', 'deposit')
            ->latest()
            ->take(5)
            ->get();

        // Valeur du portefeuille = solde + trading_balance + P&L ouvert non réalisé
        $unrealisedPnl = $openTrades->sum('profit_loss');
        $portfolioValue = ($wallet ? (float) $wallet->balance + (float) $wallet->trading_balance : 0) + $unrealisedPnl;

        return view('dashboard.index', compact(
            'user',
            'wallet',
            'openTradesCount',
            'openTradesProfitable',
            'openTradesPending',
            'realisedPnl',
            'recentTrades',
            'recentDeposits',
            'portfolioValue',
            'unrealisedPnl'
        ));
    }

    public function summary(Request $request)
    {
        $user = $request->user();
        return view('dashboard.summary', ['user' => $user]);
    }
}
