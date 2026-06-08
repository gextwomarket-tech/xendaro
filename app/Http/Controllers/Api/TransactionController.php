<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\KycLimitHelper;
use App\Helpers\WalletHelper;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->transactions()->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->paginate($request->integer('per_page', 10));

        return ApiResponse::paginated($transactions);
    }

    public function recent(Request $request): JsonResponse
    {
        $transactions = $request->user()->transactions()
            ->latest()
            ->limit($request->integer('limit', 5))
            ->get();

        return ApiResponse::success($transactions);
    }

    public function deposit(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:10',
            'method' => 'required|string|in:card,bank_transfer,crypto,mobile_money',
            'details' => 'nullable|array',
            'proof' => 'nullable|file|max:5120',
        ]);

        $proofUrl = null;
        if ($request->hasFile('proof')) {
            $proofUrl = $request->file('proof')->store('deposits/' . $request->user()->id, 'public');
        }

        $transaction = $request->user()->transactions()->create([
            'type' => 'deposit',
            'amount' => $data['amount'],
            'method' => $data['method'],
            'status' => 'pending',
            'reference' => 'DEP-' . strtoupper(uniqid()),
            'currency' => $request->user()->wallet?->currency ?? 'USD',
            'details' => $data['details'] ?? null,
            'proof_url' => $proofUrl,
        ]);

        return ApiResponse::success($transaction, 'Demande de dépôt créée', 201);
    }

    public function withdraw(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:10',
            'method' => 'required|string|in:bank_transfer,crypto,mobile_money',
            'details' => 'nullable|array',
        ]);

        $user = $request->user();
        $wallet = $user->wallet;

        if (! $wallet || $wallet->balance < $data['amount']) {
            return ApiResponse::error('Solde insuffisant', 422);
        }

        if (! KycLimitHelper::canWithdraw($user, (float) $data['amount'])) {
            return ApiResponse::error('Limite de retrait KYC dépassée', 422);
        }

        $transaction = $user->transactions()->create([
            'type' => 'withdraw',
            'amount' => $data['amount'],
            'method' => $data['method'],
            'status' => 'pending',
            'reference' => 'WIT-' . strtoupper(uniqid()),
            'currency' => $wallet->currency,
            'details' => $data['details'] ?? null,
        ]);

        return ApiResponse::success($transaction, 'Demande de retrait créée', 201);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="transactions.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $transactions = $request->user()->transactions()->latest()->get();

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Type', 'Amount', 'Method', 'Status', 'Reference']);
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->created_at,
                    $t->type,
                    $t->amount,
                    $t->method,
                    $t->status,
                    $t->reference,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function transfer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $sender = $request->user();
        $recipient = \App\Models\User::where('email', $data['recipient_email'])->first();

        // Vérifier que l'utilisateur n'envoie pas de l'argent à lui-même
        if ($sender->id === $recipient->id) {
            return ApiResponse::error('Vous ne pouvez pas envoyer de l\'argent à vous-même', 422);
        }

        $senderWallet = $sender->wallet;
        $recipientWallet = $recipient->wallet;

        // Vérifier les soldes
        if (! $senderWallet || $senderWallet->balance < $data['amount']) {
            return ApiResponse::error('Solde insuffisant pour effectuer le transfert', 422);
        }

        if (! $recipientWallet) {
            return ApiResponse::error('Le destinataire n\'a pas de portefeuille', 422);
        }

        try {
            $result = DB::transaction(function () use ($sender, $recipient, $senderWallet, $recipientWallet, $data) {
                // WalletHelper::updateBalance applique lockForUpdate internement
                // et lève RuntimeException si solde insuffisant
                WalletHelper::updateBalance($sender->id,    WalletHelper::SUBTRACT, (float) $data['amount']);
                WalletHelper::updateBalance($recipient->id, WalletHelper::ADD,      (float) $data['amount']);

                $ref = 'TRF-' . strtoupper(uniqid());

                $senderTransaction = $sender->transactions()->create([
                    'type'      => 'transfer',
                    'amount'    => $data['amount'],
                    'method'    => 'internal_transfer',
                    'status'    => 'completed',
                    'reference' => $ref . '-OUT',
                    'currency'  => $senderWallet->currency,
                    'details'   => [
                        'recipient_email' => $data['recipient_email'],
                        'recipient_id'    => $recipient->id,
                        'direction'       => 'outgoing',
                    ],
                ]);

                $recipient->transactions()->create([
                    'type'      => 'transfer',
                    'amount'    => $data['amount'],
                    'method'    => 'internal_transfer',
                    'status'    => 'completed',
                    'reference' => $ref . '-IN',
                    'currency'  => $recipientWallet->currency,
                    'details'   => [
                        'sender_email' => $sender->email,
                        'sender_id'    => $sender->id,
                        'direction'    => 'incoming',
                    ],
                ]);

                return $senderTransaction;
            });

            return ApiResponse::success([
                'sender_transaction' => $result,
                'recipient_email'    => $data['recipient_email'],
                'amount'             => $data['amount'],
            ], 'Transfert effectué avec succès', 201);

        } catch (\RuntimeException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error('Erreur lors du transfert. Veuillez réessayer.', 500);
        }
    }
}
