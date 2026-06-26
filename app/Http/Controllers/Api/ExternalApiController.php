<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * ExternalApiController
 *
 * 4 endpoints publics sécurisés par clé API (header X-API-KEY).
 * Utilisés pour intégrations externes, bots, dashboards tiers, etc.
 *
 * Routes:
 *   GET  /api/v1/user          → Infos utilisateur par email
 *   POST /api/v1/positions     → Insérer une position
 *   POST /api/v1/history       → Insérer un historique de trade
 *   PATCH /api/v1/users/update → Mettre à jour un utilisateur
 */
class ExternalApiController extends Controller
{
    /** Clé API simple — à déplacer dans .env en production */
    private const API_KEY = 'xendaro-api-2026-secret';

    // ── Middleware : vérifie le header X-API-KEY ──────────────────────────
    private function checkApiKey(Request $request): ?JsonResponse
    {
        $key = $request->header('X-API-KEY') ?? $request->query('api_key');
        if ($key !== self::API_KEY) {
            return response()->json([
                'success' => false,
                'error'   => 'Unauthorized — clé API invalide ou manquante.',
            ], 401);
        }
        return null;
    }

    // ── ENDPOINT 1 : GET /api/v1/user ─────────────────────────────────────
    // Paramètre obligatoire : email (en body JSON ou query param)
    public function getUser(Request $request): JsonResponse
    {
        if ($err = $this->checkApiKey($request)) return $err;

        $request->validate(['email' => 'required|email']);

        $user = User::with('wallet')
            ->where('email', $request->input('email'))
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error'   => 'Utilisateur introuvable.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user'    => [
                'id'                 => $user->id,
                'first_name'         => $user->first_name,
                'last_name'          => $user->last_name,
                'email'              => $user->email,
                'phone'              => $user->phone,
                'country'            => $user->country,
                'city'               => $user->city,
                'address'            => $user->address,
                'kyc_status'         => $user->kyc_status,
                'kyc_level'          => $user->kyc_level,
                'status'             => $user->status,
                'preferred_currency' => $user->preferred_currency,
                'referral_code'      => $user->referral_code,
                'created_at'         => $user->created_at?->toIso8601String(),
                'balance'            => [
                    'real'        => (float) ($user->wallet->balance ?? 0),
                    'demo'        => (float) ($user->wallet->demo_balance ?? 0),
                    'margin_used' => (float) ($user->wallet->margin_used ?? 0),
                ],
            ],
        ]);
    }

    // ── ENDPOINT 2 : POST /api/v1/positions ──────────────────────────────
    // Insère une nouvelle position dans la table `trades` (status: open)
    public function insertPosition(Request $request): JsonResponse
    {
        if ($err = $this->checkApiKey($request)) return $err;

        $v = $request->validate([
            'email'        => 'required|email|exists:users,email',
            'symbol'       => 'required|string|max:20',
            'direction'    => 'required|in:BUY,SELL',
            'volume'       => 'required|numeric|min:0.0001',
            'entry_price'  => 'required|numeric|min:0',
            'stop_loss'    => 'nullable|numeric|min:0',
            'take_profit'  => 'nullable|numeric|min:0',
            'margin'       => 'required|numeric|min:0',
            'contract_size'=> 'required|numeric|min:0',
            'account_type' => 'required|in:demo,real',
            'is_bot'       => 'nullable|boolean',
        ]);

        $user       = User::where('email', $v['email'])->first();
        $instrument = \App\Models\Instrument::where('symbol', $v['symbol'])->first();

        DB::beginTransaction();
        try {
            $trade = Trade::create([
                'user_id'       => $user->id,
                'instrument_id' => $instrument?->id,
                'account_type'  => $v['account_type'],
                'direction'     => $v['direction'],
                'volume'        => $v['volume'],
                'margin'        => $v['margin'],
                'contract_size' => $v['contract_size'],
                'entry_price'   => $v['entry_price'],
                'stop_loss'     => $v['stop_loss'] ?? null,
                'take_profit'   => $v['take_profit'] ?? null,
                'profit_loss'   => 0,
                'status'        => 'open',
                'is_bot'        => $v['is_bot'] ?? false,
                'opened_at'     => now(),
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Position insérée avec succès.',
                'position' => [
                    'id'           => $trade->id,
                    'symbol'       => $v['symbol'],
                    'direction'    => $trade->direction,
                    'volume'       => (float) $trade->volume,
                    'entry_price'  => (float) $trade->entry_price,
                    'account_type' => $trade->account_type,
                    'status'       => $trade->status,
                    'opened_at'    => $trade->opened_at->toIso8601String(),
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ── ENDPOINT 3 : POST /api/v1/history ────────────────────────────────
    // Insère un trade fermé dans l'historique (status: closed)
    public function insertHistory(Request $request): JsonResponse
    {
        if ($err = $this->checkApiKey($request)) return $err;

        $v = $request->validate([
            'email'         => 'required|email|exists:users,email',
            'symbol'        => 'required|string|max:20',
            'direction'     => 'required|in:BUY,SELL',
            'volume'        => 'required|numeric|min:0.0001',
            'entry_price'   => 'required|numeric|min:0',
            'exit_price'    => 'required|numeric|min:0',
            'profit_loss'   => 'required|numeric',
            'margin'        => 'required|numeric|min:0',
            'contract_size' => 'required|numeric|min:0',
            'account_type'  => 'required|in:demo,real',
            'close_reason'  => 'nullable|string|max:100',
            'opened_at'     => 'nullable|date',
            'closed_at'     => 'nullable|date',
            'is_bot'        => 'nullable|boolean',
        ]);

        $user       = User::where('email', $v['email'])->first();
        $instrument = \App\Models\Instrument::where('symbol', $v['symbol'])->first();

        $openedAt  = isset($v['opened_at']) ? \Carbon\Carbon::parse($v['opened_at']) : now()->subMinutes(rand(30, 90));
        $closedAt  = isset($v['closed_at']) ? \Carbon\Carbon::parse($v['closed_at']) : now();
        $duration  = $closedAt->diffInSeconds($openedAt);

        DB::beginTransaction();
        try {
            $trade = Trade::create([
                'user_id'          => $user->id,
                'instrument_id'    => $instrument?->id,
                'account_type'     => $v['account_type'],
                'direction'        => $v['direction'],
                'volume'           => $v['volume'],
                'margin'           => $v['margin'],
                'contract_size'    => $v['contract_size'],
                'entry_price'      => $v['entry_price'],
                'exit_price'       => $v['exit_price'],
                'profit_loss'      => $v['profit_loss'],
                'status'           => 'closed',
                'close_reason'     => $v['close_reason'] ?? 'Externe',
                'is_bot'           => $v['is_bot'] ?? false,
                'opened_at'        => $openedAt,
                'closed_at'        => $closedAt,
                'duration_seconds' => $duration,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Historique de trade inséré avec succès.',
                'trade'   => [
                    'id'          => $trade->id,
                    'symbol'      => $v['symbol'],
                    'direction'   => $trade->direction,
                    'profit_loss' => (float) $trade->profit_loss,
                    'status'      => $trade->status,
                    'closed_at'   => $closedAt->toIso8601String(),
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ── ENDPOINT 4 : PATCH /api/v1/users/update ──────────────────────────
    // Met à jour les données d'un utilisateur identifié par email
    public function updateUser(Request $request): JsonResponse
    {
        if ($err = $this->checkApiKey($request)) return $err;

        $v = $request->validate([
            'email'              => 'required|email|exists:users,email',
            'first_name'         => 'nullable|string|max:100',
            'last_name'          => 'nullable|string|max:100',
            'phone'              => 'nullable|string|max:20',
            'country'            => 'nullable|string|max:100',
            'city'               => 'nullable|string|max:100',
            'address'            => 'nullable|string|max:255',
            'kyc_status'         => 'nullable|in:pending,verified,rejected,not_submitted',
            'status'             => 'nullable|in:active,inactive,suspended,banned',
            'preferred_currency' => 'nullable|string|max:10',
            // Soldes (mis à jour directement sur le wallet)
            'balance'            => 'nullable|numeric|min:0',
            'demo_balance'       => 'nullable|numeric|min:0',
        ]);

        $user = User::where('email', $v['email'])->first();

        DB::beginTransaction();
        try {
            // Champs user
            $userFields = array_filter([
                'first_name'         => $v['first_name'] ?? null,
                'last_name'          => $v['last_name'] ?? null,
                'phone'              => $v['phone'] ?? null,
                'country'            => $v['country'] ?? null,
                'city'               => $v['city'] ?? null,
                'address'            => $v['address'] ?? null,
                'kyc_status'         => $v['kyc_status'] ?? null,
                'status'             => $v['status'] ?? null,
                'preferred_currency' => $v['preferred_currency'] ?? null,
            ], fn($val) => !is_null($val));

            if (!empty($userFields)) {
                $user->update($userFields);
            }

            // Mise à jour des soldes si fournis
            if (isset($v['balance']) || isset($v['demo_balance'])) {
                $wallet = \App\Models\Wallet::firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0, 'demo_balance' => 10000]
                );
                if (isset($v['balance']))      $wallet->balance      = $v['balance'];
                if (isset($v['demo_balance'])) $wallet->demo_balance = $v['demo_balance'];
                $wallet->save();
            }

            DB::commit();

            $user->load('wallet');
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès.',
                'user'    => [
                    'id'         => $user->id,
                    'email'      => $user->email,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'kyc_status' => $user->kyc_status,
                    'status'     => $user->status,
                    'balance'    => [
                        'real' => (float) ($user->wallet->balance ?? 0),
                        'demo' => (float) ($user->wallet->demo_balance ?? 0),
                    ],
                    'updated_at' => $user->updated_at->toIso8601String(),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
