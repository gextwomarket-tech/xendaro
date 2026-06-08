<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        $openTrades = $user->trades()->open()->with('instrument')->latest('opened_at')->limit(10)->get();
        $todayPnl = $user->trades()->whereDate('closed_at', today())->sum('profit_loss');
        $totalOpen = $user->trades()->open()->count();

        $recentTransactions = $user->transactions()->latest()->limit(5)->get();

        $balanceHistory = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayPnl = $user->trades()
                ->whereDate('closed_at', $date)
                ->sum('profit_loss');
            $balanceHistory[] = [
                'date' => $date->format('Y-m-d'),
                'balance' => round(($wallet?->balance ?? 0) + $dayPnl, 2),
            ];
        }

        return ApiResponse::success([
            'welcome' => 'Bonjour ' . $user->first_name,
            'balance' => $wallet?->balance ?? 0,
            'trading_balance' => $wallet?->trading_balance ?? 0,
            'today_pnl' => $todayPnl,
            'open_positions_count' => $totalOpen,
            'margin_used' => $wallet?->margin_used ?? 0,
            'open_positions' => $openTrades,
            'recent_transactions' => $recentTransactions,
            'balance_history' => $balanceHistory,
            'kyc_status' => $user->kyc_status,
            'kyc_level' => $user->kyc_level,
        ]);
    }
}
