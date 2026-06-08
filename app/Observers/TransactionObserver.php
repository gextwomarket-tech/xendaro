<?php

namespace App\Observers;

use App\Helpers\WalletHelper;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Aucune modification de solde à la création.
     *
     * - Dépôt  : l'argent n'est pas encore reçu → solde inchangé
     * - Retrait : l'argent n'est pas encore envoyé → solde inchangé
     * - Transfert : géré directement par le contrôleur (déjà atomique)
     */
    public function created(Transaction $transaction): void
    {
        // Intentionnellement vide.
        // Toute modification de solde passe par updated() → statut 'completed'.
    }

    /**
     * Mise à jour du solde uniquement lors du passage au statut 'completed'.
     *
     * Règles :
     *   - Le guard `processed_at IS NULL` empêche le double-traitement.
     *   - Seul le passage → 'completed' modifie le solde.
     *   - Le passage → 'failed' ne modifie PAS le solde (rien n'a encore été touché)
     *     et ne fixe PAS processed_at, permettant à l'admin de retry via 'completed'.
     *   - Les transferts sont exclus : le contrôleur les gère de façon atomique.
     */
    public function updated(Transaction $transaction): void
    {
        $previousStatus = $transaction->getOriginal('status');
        $newStatus      = $transaction->status;

        // Aucun changement de statut → rien à faire
        if ($previousStatus === $newStatus) {
            return;
        }

        // Les transferts sont gérés en dehors de cet observer (WalletController / API)
        if ($transaction->type === 'transfer') {
            return;
        }

        // ── Passage → 'completed' ──────────────────────────────────────────────
        if ($newStatus === 'completed' && is_null($transaction->processed_at)) {
            $this->processCompleted($transaction);
            return;
        }

        // ── Passage → 'failed' ────────────────────────────────────────────────
        // Rien à faire sur le solde : on n'a jamais modifié le solde à la création.
        // processed_at reste NULL pour permettre une correction via 'completed'.
        if ($newStatus === 'failed') {
            // Log pour traçabilité
            Log::info("Transaction #{$transaction->id} ({$transaction->type}) marquée failed. Aucun solde modifié.");
            return;
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Applique la modification de solde pour une transaction complétée.
     * Utilise WalletHelper (lockForUpdate + DB transaction) pour être atomique.
     */
    private function processCompleted(Transaction $transaction): void
    {
        $userId = $transaction->user_id;
        $amount = (float) $transaction->amount;

        try {
            if ($transaction->type === 'deposit') {
                // Crédit du solde + mise à jour du total déposé
                WalletHelper::updateBalance($userId, WalletHelper::ADD, $amount);

                \App\Models\Wallet::where('user_id', $userId)
                    ->increment('total_deposited', $amount);

            } elseif ($transaction->type === 'withdraw') {
                // Débit du solde — lève une RuntimeException si solde insuffisant
                WalletHelper::updateBalance($userId, WalletHelper::SUBTRACT, $amount);

                \App\Models\Wallet::where('user_id', $userId)
                    ->increment('total_withdrawn', $amount);
            }

            // Marquer comme traité pour empêcher tout double-traitement futur
            $transaction->processed_at = now();
            $transaction->saveQuietly();

            Log::info("Transaction #{$transaction->id} ({$transaction->type}, {$amount}) complétée. Solde mis à jour pour user #{$userId}.");

        } catch (\RuntimeException $e) {
            // Solde insuffisant pour un retrait : on repasse la transaction en 'failed'
            // sans toucher processed_at (permettant une correction ultérieure)
            Log::error("Transaction #{$transaction->id} ({$transaction->type}) — échec du traitement : {$e->getMessage()}");

            $transaction->status     = 'failed';
            $transaction->admin_note = 'Échec automatique : ' . $e->getMessage();
            $transaction->saveQuietly();

        } catch (\Exception $e) {
            Log::critical("Transaction #{$transaction->id} — erreur inattendue lors du traitement du solde : {$e->getMessage()}");
        }
    }
}
