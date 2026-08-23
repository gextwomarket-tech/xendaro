<?php

namespace App\Services;

use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

/**
 * Encapsule la logique de credit/debit du Wallet lors de la validation
 * admin d'une WalletTransaction (depot/retrait), voir xendaro-fox-plan.json
 * Page id 36 "wallet" > popup_modal Depot/Retrait.
 *
 * Regle: le solde_reel du Wallet n'est jamais modifie directement a la creation
 * de la transaction (statut 'en_attente'), uniquement lorsque l'admin fait
 * passer le statut a 'valide' (depot => credit, retrait => debit).
 */
class WalletTransactionService
{
    public static function approve(WalletTransaction $transaction, ?string $noteAdmin = null): void
    {
        if ($transaction->statut === 'valide') {
            return;
        }

        DB::transaction(function () use ($transaction, $noteAdmin) {
            $wallet = $transaction->user->wallet;

            if ($wallet) {
                if ($transaction->type === 'depot') {
                    $wallet->increment('solde_reel', $transaction->montant);
                } elseif ($transaction->type === 'retrait') {
                    $wallet->decrement('solde_reel', $transaction->montant);
                }
            }

            $transaction->update([
                'statut' => 'valide',
                'note_admin' => $noteAdmin ?? $transaction->note_admin,
            ]);
        });
    }

    public static function reject(WalletTransaction $transaction, ?string $noteAdmin = null): void
    {
        $transaction->update([
            'statut' => 'refuse',
            'note_admin' => $noteAdmin ?? $transaction->note_admin,
        ]);
    }
}
