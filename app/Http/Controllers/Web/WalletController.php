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
        
        // Defense in depth: Verify KYC status (middleware already does this, but safety check)
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez compléter votre vérification KYC pour accéder à votre portefeuille.');
        }

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
    public function storeDeposit(Request $request)
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

            return redirect()->route('wallet.index')
                ->with('success', 'Demande de dépôt soumise. En attente de validation.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la soumission du dépôt.')
                ->withInput();
        }
    }

    /**
     * Store a withdrawal request
     */
    public function storeWithdraw(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;
        $limits = PlatformHelper::withdrawalLimits();

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => "required|numeric|min:{$limits['min']}|max:{$limits['max']}",
        ], [
            'amount.min' => "Montant minimum: {$limits['min']}",
            'amount.max' => "Montant maximum: {$limits['max']}",
        ]);

        $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);

        // Verify amount is not greater than balance
        if ($validated['amount'] > $wallet->balance) {
            return redirect()->back()
                ->with('error', 'Solde insuffisant pour effectuer ce retrait.')
                ->withInput();
        }

        try {
            // Calculate withdrawal fees (1%)
            $amount = $validated['amount'];
            $fees = $amount * 0.01;
            $netAmount = $amount - $fees;

            // Create transaction with pending status
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $amount,
                'method' => $paymentMethod->label,
                'status' => 'pending',
                'currency' => $wallet->currency ?? 'USD',
                'reference' => Str::upper(Str::random(10)),
                'details' => [
                    'payment_method_type' => $paymentMethod->type,
                    'payment_method_details' => $paymentMethod->details,
                    'fees' => $fees,
                    'net_amount' => $netAmount,
                ],
            ]);

            return redirect()->route('wallet.index')
                ->with('success', 'Demande de retrait soumise. En attente de validation.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la soumission du retrait.')
                ->withInput();
        }
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
                    "Bonjour {$sender->name},\n\nVotre transfert de {$amount} {$currency} vers {$validated['recipient_email']} a bien été effectué.\nRéférence : {$ref}\n\nMoon Trade",
                    fn($m) => $m->to($sender->email)->subject('Transfert envoyé - Moon Trade')
                );

                Mail::raw(
                    "Bonjour {$recipient->name},\n\nVous avez reçu un transfert de {$amount} {$currency} de la part de {$sender->email}.\nCe montant est disponible dans votre portefeuille.\n\nMoon Trade",
                    fn($m) => $m->to($recipient->email)->subject('Transfert reçu - Moon Trade')
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
