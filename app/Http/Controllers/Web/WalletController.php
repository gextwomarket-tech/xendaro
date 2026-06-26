<?php

namespace App\Http\Controllers\Web;

use App\Helpers\PlatformHelper;
use App\Helpers\WalletHelper;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WalletController extends Controller
{  
    /**
     * Show wallet page with deposit/withdraw forms
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Refresh wallet data from database
        $wallet = Wallet::where('user_id', $user->id)->first();
        $isWalletNull = is_null($wallet);
        $paymentMethods = PaymentMethod::all();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('dashboard.wallet', [
            'wallet' => $wallet,
            'isWalletNull' => $isWalletNull,
            'paymentMethods' => $paymentMethods,
            'transactions' => $transactions,
            'depositLimits' => PlatformHelper::depositLimits(),
            'withdrawalLimits' => PlatformHelper::withdrawalLimits(),
        ]);
    }

    /**
     * Create a wallet for the user with default values
     */
    public function createWallet(Request $request)
    {
        $user = $request->user();
        
        // Check if wallet already exists
        if ($user->wallet) {
            return redirect()->route('wallet.index')
                ->with('info', 'Vous avez déjà un portefeuille.');
        }

        try {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'trading_balance' => 0,
                'demo_balance' => 0,
                'total_deposited' => 0,
                'total_withdrawn' => 0,
                'margin_used' => 0,
                'currency' => 'USD',
            ]);

            return redirect()->route('wallet.index')
                ->with('success', 'Portefeuille créé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du portefeuille.');
        }
    }

    /**
     * Store a deposit request
     */
    public function storeDeposit(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;
        $limits = PlatformHelper::depositLimits();

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => "required|numeric|min:{$limits['min']}|max:{$limits['max']}",
        ], [
            'amount.min' => "Montant minimum: {$limits['min']}",
            'amount.max' => "Montant maximum: {$limits['max']}",
        ]);

        $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);

        try {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $validated['amount'],
                'method' => $paymentMethod->label,
                'status' => 'pending',
                'currency' => $wallet->currency ?? 'USD',
                'reference' => Str::upper(Str::random(10)),
                'details' => [
                    'payment_method_type' => $paymentMethod->type,
                    'payment_method_details' => $paymentMethod->details,
                ],
            ]);

            $amount   = number_format((float) $validated['amount'], 2);
            $currency = $wallet->currency ?? 'USD';

            DB::table('notifications')->insert([
                'id'              => Str::uuid()->toString(),
                'type'            => 'deposit',
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode([
                    'message' => "Demande de dépôt de {$amount} {$currency} soumise. En attente de validation.",
                    'amount'  => $validated['amount'],
                    'ref'     => $transaction->reference,
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            try {
                Mail::raw(
                    "Bonjour {$user->name},\n\nVotre demande de dépôt de {$amount} {$currency} a bien été reçue et est en attente de validation.\nRéférence : {$transaction->reference}\n\nPurprime Fox",
                    fn($m) => $m->to($user->email)->subject('Demande de dépôt reçue - Purprime Fox')
                );
            } catch (\Exception) {
                // Mail non bloquant
            }

            return response()->json([
                'message' => 'Demande de dépôt soumise. En attente de validation.',
                'data' => [
                    'reference' => $transaction->reference,
                    'amount'    => $validated['amount'],
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la soumission du dépôt.',
            ], 500);
        }
    }

    /**
     * Store a withdrawal request
     */
    public function storeWithdraw(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;
        $limits = PlatformHelper::withdrawalLimits();

        // Validation de base
        $validated = $request->validate([
            'amount' => "required|numeric|min:{$limits['min']}|max:{$limits['max']}",
            'payment_method' => 'required|in:bank_transfer,cryptocurrency,card',
        ], [
            'amount.min' => "Montant minimum: {$limits['min']}",
            'amount.max' => "Montant maximum: {$limits['max']}",
        ]);

        // Verify amount is not greater than balance
        if ($validated['amount'] > $wallet->balance) {
            return response()->json([
                'message' => 'Solde insuffisant pour effectuer ce retrait.',
            ], 422);
        }

        // Validation des champs spécifiques à la méthode
        $methodValidation = match ($validated['payment_method']) {
            'bank_transfer' => [
                'bank_account_holder' => 'required|string|min:2|max:100',
                'bank_account_number' => 'required|string|min:15|max:34',
                'bank_name' => 'required|string|min:2|max:100',
                'bank_swift' => 'nullable|string|max:11',
            ],
            'cryptocurrency' => [
                'crypto_type' => 'required|in:bitcoin,ethereum,litecoin,ripple,dogecoin,usdt',
                'crypto_address' => 'required|string|min:26|max:100',
            ],
            'card' => [
                'card_holder_name' => 'required|string|min:2|max:100',
                'card_number' => 'required|string|size:4',
                'card_expiry' => 'required|regex:/^\d{2}\/\d{2}$/',
                'card_bank_name' => 'required|string|min:2|max:100',
            ],
            default => []
        };

        // Effectuer la validation complète
        $fullValidation = array_merge($validated, $request->validate($methodValidation));

        try {
            // Calculate withdrawal fees (1%)
            $amount = $fullValidation['amount'];
            $fees = $amount * 0.01;
            $netAmount = $amount - $fees;

            // Create withdrawal request (new model)
            $withdrawalRequest = \App\Models\WithdrawalRequest::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'fees' => $fees,
                'net_amount' => $netAmount,
                'currency' => $wallet->currency ?? 'USD',
                'payment_method' => $fullValidation['payment_method'],
                'status' => 'pending',
                'reference' => Str::upper(Str::random(10)),
                'bank_account_number' => $fullValidation['bank_account_number'] ?? null,
                'bank_account_holder' => $fullValidation['bank_account_holder'] ?? null,
                'bank_swift' => $fullValidation['bank_swift'] ?? null,
                'bank_name' => $fullValidation['bank_name'] ?? null,
                'crypto_type' => $fullValidation['crypto_type'] ?? null,
                'crypto_address' => $fullValidation['crypto_address'] ?? null,
                'card_holder_name' => $fullValidation['card_holder_name'] ?? null,
                'card_number' => $fullValidation['card_number'] ?? null,
                'card_expiry' => $fullValidation['card_expiry'] ?? null,
                'card_bank_name' => $fullValidation['card_bank_name'] ?? null,
            ]);

            // Also create transaction record for history
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $amount,
                'method' => 'Retrait - ' . $this->getPaymentMethodLabel($fullValidation['payment_method']),
                'status' => 'pending',
                'currency' => $wallet->currency ?? 'USD',
                'reference' => $withdrawalRequest->reference,
                'details' => [
                    'withdrawal_request_id' => $withdrawalRequest->id,
                    'payment_method' => $fullValidation['payment_method'],
                    'fees' => $fees,
                    'net_amount' => $netAmount,
                ],
            ]);

            $currency = $wallet->currency ?? 'USD';

            DB::table('notifications')->insert([
                'id'              => Str::uuid()->toString(),
                'type'            => 'withdraw',
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode([
                    'message' => 'Demande de retrait de ' . number_format($amount, 2) . " {$currency} soumise. En attente de validation.",
                    'amount'  => $amount,
                    'ref'     => $withdrawalRequest->reference,
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            try {
                Mail::raw(
                    "Bonjour {$user->name},\n\nVotre demande de retrait de " . number_format($amount, 2) . " {$currency} a bien été reçue et est en attente de validation.\nMontant net après frais : " . number_format($netAmount, 2) . " {$currency}\nRéférence : {$withdrawalRequest->reference}\n\nPurprime Fox",
                    fn($m) => $m->to($user->email)->subject('Demande de retrait reçue - Purprime Fox')
                );
            } catch (\Exception) {
                // Mail non bloquant
            }

            return response()->json([
                'message' => 'Demande de retrait soumise. En attente de validation.',
                'data' => [
                    'reference'  => $withdrawalRequest->reference,
                    'amount'     => $amount,
                    'fees'       => $fees,
                    'net_amount' => $netAmount,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la soumission du retrait.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment method label
     */
    private function getPaymentMethodLabel(string $method): string
    {
        return match ($method) {
            'bank_transfer' => 'Virement bancaire',
            'cryptocurrency' => 'Cryptomonnaie',
            'card' => 'Retrait par Carte bancaire',
            default => 'Retrait'
        };
    }

    /**
     * Transfert interne entre deux comptes (route web, session auth)
     */
    public function storeTransfer(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount'          => 'required|numeric|min:0.01',
        ], [
            'recipient_email.exists' => 'Aucun compte trouvé avec cet email.',
            'amount.min'             => 'Le montant minimum est 0.01.',
        ]);

        $sender    = $request->user();
        $recipient = User::where('email', $validated['recipient_email'])->first();

        if ($sender->id === $recipient->id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous transférer de l\'argent à vous-même.'], 422);
        }

        $senderWallet    = $sender->wallet;
        $recipientWallet = $recipient->wallet;

        if (! $senderWallet) {
            return response()->json(['message' => 'Vous n\'avez pas de portefeuille actif.'], 422);
        }

        if (! $recipientWallet) {
            return response()->json(['message' => 'Le destinataire n\'a pas encore de portefeuille.'], 422);
        }

        // Vérification rapide avant la DB transaction (pas de lock ici, juste un guard UX)
        if (WalletHelper::getBalance($sender->id) < $validated['amount']) {
            return response()->json(['message' => 'Solde insuffisant pour effectuer ce transfert.'], 422);
        }

        try {
            $senderTx = DB::transaction(function () use ($sender, $recipient, $recipientWallet, $validated) {
                // WalletHelper::updateBalance applique lockForUpdate internement
                // et lève RuntimeException si solde insuffisant (double protection)
                WalletHelper::updateBalance($sender->id, WalletHelper::SUBTRACT, (float) $validated['amount']);
                WalletHelper::updateBalance($recipient->id, WalletHelper::ADD,      (float) $validated['amount']);

                $ref = 'TRF-' . Str::upper(Str::random(8));

                $senderTx = Transaction::create([
                    'user_id'   => $sender->id,
                    'type'      => 'transfer',
                    'amount'    => $validated['amount'],
                    'method'    => 'internal_transfer',
                    'status'    => 'completed',
                    'reference' => $ref . '-OUT',
                    'currency'  => $senderWallet->currency ?? 'USD',
                    'details'   => [
                        'recipient_email' => $validated['recipient_email'],
                        'recipient_id'    => $recipient->id,
                        'direction'       => 'outgoing',
                    ],
                ]);

                Transaction::create([
                    'user_id'   => $recipient->id,
                    'type'      => 'transfer',
                    'amount'    => $validated['amount'],
                    'method'    => 'internal_transfer',
                    'status'    => 'completed',
                    'reference' => $ref . '-IN',
                    'currency'  => $recipientWallet->currency ?? 'USD',
                    'details'   => [
                        'sender_email' => $sender->email,
                        'sender_id'    => $sender->id,
                        'direction'    => 'incoming',
                    ],
                ]);

                // Notifications en base pour les 2 utilisateurs
                $now = now();
                DB::table('notifications')->insert([
                    [
                        'id'              => Str::uuid()->toString(),
                        'type'            => 'transfer',
                        'notifiable_type' => User::class,
                        'notifiable_id'   => $sender->id,
                        'data'            => json_encode([
                            'message' => 'Transfert de ' . number_format($validated['amount'], 2) . ' USD envoyé à ' . $validated['recipient_email'],
                            'amount'  => $validated['amount'],
                            'ref'     => $ref . '-OUT',
                        ]),
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ],
                    [
                        'id'              => Str::uuid()->toString(),
                        'type'            => 'transfer',
                        'notifiable_type' => User::class,
                        'notifiable_id'   => $recipient->id,
                        'data'            => json_encode([
                            'message' => 'Transfert de ' . number_format($validated['amount'], 2) . ' USD reçu de ' . $sender->email,
                            'amount'  => $validated['amount'],
                            'ref'     => $ref . '-IN',
                        ]),
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ],
                ]);

                return $senderTx;
            });

            // Emails (silencieux si échec mailer non configuré)
            try {
                $amount    = number_format((float) $validated['amount'], 2);
                $currency  = $senderWallet->currency ?? 'USD';
                $ref       = $senderTx->reference;

                Mail::raw(
                    "Bonjour {$sender->name},\n\nVotre transfert de {$amount} {$currency} vers {$validated['recipient_email']} a bien été effectué.\nRéférence : {$ref}\n\nPurprime Fox",
                    fn($m) => $m->to($sender->email)->subject('Transfert envoyé - Purprime Fox')
                );

                Mail::raw(
                    "Bonjour {$recipient->name},\n\nVous avez reçu un transfert de {$amount} {$currency} de la part de {$sender->email}.\nCe montant est disponible dans votre portefeuille.\n\nPurprime Fox",
                    fn($m) => $m->to($recipient->email)->subject('Transfert reçu - Purprime Fox')
                );
            } catch (\Exception) {
                // Mail non bloquant
            }

            return response()->json([
                'message' => 'Transfert effectué avec succès.',
                'data'    => [
                    'sender_transaction' => ['reference' => $senderTx->reference],
                    'recipient_email'    => $validated['recipient_email'],
                    'amount'             => $validated['amount'],
                ],
            ], 200);

        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors du transfert. Veuillez réessayer.'], 500);
        }
    }

    public function balance(Request $request)
    {
        return view('wallet.balance', ['wallet' => $request->user()->wallet]);
    }

    public function summary(Request $request)
    {
        return view('wallet.summary', ['wallet' => $request->user()->wallet]);
    }
}
