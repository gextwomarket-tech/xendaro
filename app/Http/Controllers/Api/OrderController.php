<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\PriceService;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Order;
use App\Models\Trade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()->orders()
            ->with('instrument')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return ApiResponse::success($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'instrument_id' => 'required|exists:instruments,id',
            'type' => 'required|string|in:MARKET,LIMIT,STOP,STOP_LIMIT',
            'direction' => 'required|string|in:BUY,SELL',
            'volume' => 'required|numeric|min:0.01',
            'price' => 'nullable|numeric|required_if:type,LIMIT,STOP,STOP_LIMIT',
            'stop_loss' => 'nullable|numeric',
            'take_profit' => 'nullable|numeric',
        ]);

        $user = $request->user();
        $instrument = Instrument::find($data['instrument_id']);
        $price = PriceService::getMidPrice($instrument);
        $specs = $instrument->specs ?? [];
        $leverage = $specs['leverage'] ?? 10;
        $contractSize = $specs['contract_size'] ?? 1;
        $posValue = $data['volume'] * $price * $contractSize;
        $margin = $posValue / $leverage;

        // Si ordre au marché : exécution immédiate
        if (strtoupper($data['type']) === 'MARKET') {
            $wallet = $user->wallet;
            if (! $wallet || $wallet->trading_balance < $margin) {
                return ApiResponse::error('Marge insuffisante', 422);
            }

            return DB::transaction(function () use ($user, $data, $instrument, $price, $margin) {
                $order = $user->orders()->create([
                    ...$data,
                    'status' => 'executed',
                    'executed_at' => now(),
                ]);

                $trade = $user->trades()->create([
                    'instrument_id' => $data['instrument_id'],
                    'direction' => $data['direction'],
                    'volume' => $data['volume'],
                    'entry_price' => $price,
                    'stop_loss' => $data['stop_loss'] ?? null,
                    'take_profit' => $data['take_profit'] ?? null,
                    'status' => 'open',
                    'opened_at' => now(),
                ]);

                $wallet = $user->wallet;
                $wallet->decrement('trading_balance', $margin);
                $wallet->increment('margin_used', $margin);

                return ApiResponse::success([
                    'order' => $order->load('instrument'),
                    'trade' => $trade->load('instrument'),
                ], 'Ordre exécuté — position ouverte', 201);
            });
        }

        // Ordre limit/stop : en attente
        $order = $user->orders()->create([
            ...$data,
            'status' => 'pending',
        ]);

        $order->load('instrument');

        return ApiResponse::success($order, 'Ordre créé — en attente', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $order = $request->user()->orders()->find($id);

        if (! $order || $order->status !== 'pending') {
            return ApiResponse::error('Ordre introuvable ou déjà exécuté', 404);
        }

        $data = $request->validate([
            'price' => 'nullable|numeric',
            'stop_loss' => 'nullable|numeric',
            'take_profit' => 'nullable|numeric',
        ]);

        $order->update($data);

        return ApiResponse::success($order, 'Ordre mis à jour');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $order = $request->user()->orders()->find($id);

        if (! $order || $order->status !== 'pending') {
            return ApiResponse::error('Ordre introuvable ou déjà exécuté', 404);
        }

        $order->update(['status' => 'cancelled']);

        return ApiResponse::success(null, 'Ordre annulé');
    }
}
