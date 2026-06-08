<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function balance(Request $request): JsonResponse
    {
        $wallet = $request->user()->wallet;

        return ApiResponse::success([
            'balance' => $wallet?->balance ?? 0,
            'trading_balance' => $wallet?->trading_balance ?? 0,
            'total_deposited' => $wallet?->total_deposited ?? 0,
            'total_withdrawn' => $wallet?->total_withdrawn ?? 0,
            'margin_used' => $wallet?->margin_used ?? 0,
            'currency' => $wallet?->currency ?? 'USD',
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;
        $openTrades = $user->trades()->open()->count();
        $todayPnl = $user->trades()
            ->whereDate('closed_at', today())
            ->sum('profit_loss');

        return ApiResponse::success([
            'balance' => $wallet?->balance ?? 0,
            'trading_balance' => $wallet?->trading_balance ?? 0,
            'margin_used' => $wallet?->margin_used ?? 0,
            'open_trades_count' => $openTrades,
            'today_pnl' => $todayPnl,
            'equity' => ($wallet?->balance ?? 0) + $todayPnl,
        ]);
    }
}
