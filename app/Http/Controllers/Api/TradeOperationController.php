<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Trade Operation Controller - Handles real-time trading operations
 * Used by public/trade.html for live trading interface
 */
class TradeOperationController extends Controller
{
    /**
     * Open a new trade position
     * POST /api/trade/operations/open
     */
    public function openPosition(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symbol'       => 'required|string',
            'direction'    => 'required|in:BUY,SELL',
            'volume'       => 'required|numeric|min:0.01',
            'entry_price'  => 'required|numeric|min:0',
            'stop_loss'    => 'nullable|numeric|min:0',
            'take_profit'  => 'nullable|numeric|min:0',
            'margin'       => 'required|numeric|min:0',
            'contract_size'=> 'required|numeric|min:0',
            'account_type' => 'required|in:demo,real',
            'is_bot'       => 'boolean',
        ]);

        $user   = $request->user();
        $wallet = $this->getOrCreateWallet($user->id);

        // Verify available balance
        $margin = (float) $validated['margin'];
        if ($validated['account_type'] === 'demo') {
            if ($wallet->demo_balance < $margin) {
                return ApiResponse::error('Insufficient demo balance', 422);
            }
        } else {
            if ($wallet->balance < $margin) {
                return ApiResponse::error('Insufficient real balance', 422);
            }
        }

        // Get instrument
        $instrument = Instrument::where('symbol', $validated['symbol'])->first();

        DB::beginTransaction();
        try {
            // Deduct margin
            if ($validated['account_type'] === 'demo') {
                $wallet->decrement('demo_balance', $margin);
            } else {
                $wallet->decrement('balance', $margin);
                $wallet->increment('margin_used', $margin);
            }

            // Create trade
            $trade = Trade::create([
                'user_id'       => $user->id,
                'instrument_id' => $instrument?->id ?? 1,
                'account_type'  => $validated['account_type'],
                'direction'     => strtoupper($validated['direction']),
                'volume'        => (float) $validated['volume'],
                'margin'        => $margin,
                'contract_size' => (float) $validated['contract_size'],
                'entry_price'   => (float) $validated['entry_price'],
                'stop_loss'     => $validated['stop_loss'] ? (float) $validated['stop_loss'] : null,
                'take_profit'   => $validated['take_profit'] ? (float) $validated['take_profit'] : null,
                'profit_loss'   => 0,
                'status'        => 'open',
                'is_bot'        => $validated['is_bot'] ?? false,
                'opened_at'     => now(),
            ]);

            DB::commit();

            return ApiResponse::success([
                'trade_id' => $trade->id,
                'balance'  => $this->balancePayload($wallet->fresh()),
            ], 'Position opened successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('TradeOperationController::openPosition Error', [
                'user_id'   => $user->id ?? null,
                'symbol'    => $validated['symbol'] ?? null,
                'message'   => $e->getMessage(),
            ]);
            return ApiResponse::error('Server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Close a trade position
     * POST /api/trade/operations/{id}/close
     */
    public function closePosition(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'exit_price'   => 'required|numeric|min:0',
            'close_reason' => 'nullable|string|max:50',
        ]);

        $user  = $request->user();
        $trade = Trade::where('user_id', $user->id)
                      ->where('status', 'open')
                      ->find($id);

        if (!$trade) {
            return ApiResponse::error('Position not found', 404);
        }

        $wallet    = $this->getOrCreateWallet($user->id);
        $exitPrice = (float) $validated['exit_price'];

        // Calculate P&L
        $pnl = $trade->direction === 'BUY'
            ? ($exitPrice - (float)$trade->entry_price) * (float)$trade->volume * (float)$trade->contract_size
            : ((float)$trade->entry_price - $exitPrice) * (float)$trade->volume * (float)$trade->contract_size;

        $duration = now()->diffInSeconds($trade->opened_at);

        DB::beginTransaction();
        try {
            $trade->update([
                'exit_price'      => $exitPrice,
                'profit_loss'     => round($pnl, 2),
                'status'          => 'closed',
                'close_reason'    => $validated['close_reason'] ?? 'Manual',
                'closed_at'       => now(),
                'duration_seconds'=> $duration,
            ]);

            // Return margin + P&L to correct account
            $returned = (float)$trade->margin + $pnl;
            if ($trade->account_type === 'demo') {
                $wallet->increment('demo_balance', $returned);
            } else {
                $wallet->increment('balance', $returned);
                $wallet->decrement('margin_used', min($trade->margin, $wallet->margin_used));
            }

            DB::commit();

            return ApiResponse::success([
                'pnl'     => round($pnl, 2),
                'balance' => $this->balancePayload($wallet->fresh()),
            ], 'Position closed successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('TradeOperationController::closePosition Error', [
                'user_id'  => $user->id ?? null,
                'trade_id' => $id,
                'message'  => $e->getMessage(),
            ]);
            return ApiResponse::error('Server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Close all open positions
     * POST /api/trade/operations/close-all
     */
    public function closeAllPositions(Request $request): JsonResponse
    {
        $user   = $request->user();
        $wallet = $this->getOrCreateWallet($user->id);
        $trades = Trade::where('user_id', $user->id)
                       ->where('status', 'open')
                       ->get();

        if ($trades->isEmpty()) {
            return ApiResponse::success(['closed_count' => 0], 'No open positions');
        }

        DB::beginTransaction();
        try {
            $closed = 0;
            $totalPnl = 0;

            foreach ($trades as $trade) {
                // Use current price as exit
                $exitPrice = $trade->entry_price;
                $pnl = $trade->direction === 'BUY'
                    ? ($exitPrice - (float)$trade->entry_price) * (float)$trade->volume * (float)$trade->contract_size
                    : ((float)$trade->entry_price - $exitPrice) * (float)$trade->volume * (float)$trade->contract_size;

                $totalPnl += $pnl;
                $duration = now()->diffInSeconds($trade->opened_at);

                $trade->update([
                    'exit_price'      => $exitPrice,
                    'profit_loss'     => round($pnl, 2),
                    'status'          => 'closed',
                    'close_reason'    => 'Close All',
                    'closed_at'       => now(),
                    'duration_seconds'=> $duration,
                ]);

                // Return margin + P&L
                $returned = (float)$trade->margin + $pnl;
                if ($trade->account_type === 'demo') {
                    $wallet->increment('demo_balance', $returned);
                } else {
                    $wallet->increment('balance', $returned);
                    $wallet->decrement('margin_used', min($trade->margin, $wallet->margin_used));
                }

                $closed++;
            }

            DB::commit();

            return ApiResponse::success([
                'closed_count' => $closed,
                'total_pnl'    => round($totalPnl, 2),
                'balance'      => $this->balancePayload($wallet->fresh()),
            ], 'All positions closed');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('TradeOperationController::closeAllPositions Error', [
                'user_id' => $user->id ?? null,
                'message' => $e->getMessage(),
            ]);
            return ApiResponse::error('Server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get current P&L for open positions
     * GET /api/trade/operations/pnl
     */
    public function getCurrentPnl(Request $request): JsonResponse
    {
        $user = $request->user();
        $trades = Trade::where('user_id', $user->id)
                       ->where('status', 'open')
                       ->with('instrument')
                       ->get();

        $totalOpenPnl = 0;
        $positions = [];

        foreach ($trades as $trade) {
            // Use entry price as current (in real app, use websocket or API)
            $currentPrice = $trade->entry_price;
            $pnl = $trade->direction === 'BUY'
                ? ($currentPrice - (float)$trade->entry_price) * (float)$trade->volume
                : ((float)$trade->entry_price - $currentPrice) * (float)$trade->volume;

            $totalOpenPnl += $pnl;
            $positions[] = [
                'id'      => $trade->id,
                'symbol'  => $trade->instrument?->symbol ?? '—',
                'pnl'     => round($pnl, 2),
                'pnl_pct' => $trade->entry_price > 0 
                    ? round(($pnl / ((float)$trade->entry_price * (float)$trade->volume)) * 100, 2) 
                    : 0,
            ];
        }

        return ApiResponse::success([
            'total_open_pnl' => round($totalOpenPnl, 2),
            'positions'      => $positions,
            'position_count' => count($positions),
        ]);
    }

    /**
     * Update position SL/TP
     * PATCH /api/trade/operations/{id}/update-levels
     */
    public function updateLevels(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'stop_loss'   => 'nullable|numeric|min:0',
            'take_profit' => 'nullable|numeric|min:0',
        ]);

        $user  = $request->user();
        $trade = Trade::where('user_id', $user->id)
                      ->where('status', 'open')
                      ->find($id);

        if (!$trade) {
            return ApiResponse::error('Position not found', 404);
        }

        $trade->update([
            'stop_loss'   => $validated['stop_loss'] ? (float) $validated['stop_loss'] : null,
            'take_profit' => $validated['take_profit'] ? (float) $validated['take_profit'] : null,
        ]);

        return ApiResponse::success($trade, 'SL/TP updated');
    }

    /**
     * Create a pending order
     * POST /api/trade/operations/orders
     */
    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symbol'        => 'required|string',
            'direction'     => 'required|in:BUY,SELL',
            'volume'        => 'required|numeric|min:0.01',
            'order_price'   => 'required|numeric|min:0',
            'order_type'    => 'required|in:limit,stop',
            'account_type'  => 'required|in:demo,real',
        ]);

        $user = $request->user();
        $instrument = Instrument::where('symbol', $validated['symbol'])->first();

        $order = Order::create([
            'user_id'       => $user->id,
            'instrument_id' => $instrument?->id ?? 1,
            'account_type'  => $validated['account_type'],
            'direction'     => strtoupper($validated['direction']),
            'volume'        => (float) $validated['volume'],
            'order_price'   => (float) $validated['order_price'],
            'order_type'    => $validated['order_type'],
            'status'        => 'pending',
            'created_at'    => now(),
        ]);

        return ApiResponse::success($order, 'Pending order created');
    }

    /**
     * Cancel a pending order
     * DELETE /api/trade/operations/orders/{id}
     */
    public function cancelOrder(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)
                      ->where('status', 'pending')
                      ->find($id);

        if (!$order) {
            return ApiResponse::error('Order not found', 404);
        }

        $order->update(['status' => 'cancelled']);

        return ApiResponse::success([], 'Order cancelled');
    }

    /**
     * Get all pending orders
     * GET /api/trade/operations/orders
     */
    public function getPendingOrders(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
                       ->where('status', 'pending')
                       ->with('instrument')
                       ->latest()
                       ->get();

        return ApiResponse::success($orders);
    }

    // ─ PRIVATE HELPERS ─────────────────────────────────────────

    private function getOrCreateWallet(int $userId): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0, 'trading_balance' => 0, 'demo_balance' => 10000, 'margin_used' => 0]
        );
    }

    private function balancePayload(Wallet $wallet): array
    {
        return [
            'real_balance' => (float) $wallet->balance,
            'demo_balance' => (float) $wallet->demo_balance,
            'margin_used'  => (float) $wallet->margin_used,
            'free_margin'  => (float) ($wallet->balance - $wallet->margin_used),
        ];
    }
}
