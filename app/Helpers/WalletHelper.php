<?php

namespace App\Helpers;

use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletHelper
{
    /** Constantes de type pour updateBalance() */
    public const SUBTRACT = 0;
    public const ADD      = 1;

    /**
     * Retourne le solde réel et à jour de l'utilisateur depuis la base de données.
     *
     * @param  int  $userId
     * @return float  Solde actuel, ou 0.0 si le portefeuille n'existe pas
     */
    public static function getBalance(int $userId): float
    {
        $wallet = Wallet::where('user_id', $userId)
            ->select('balance')
            ->first();

        return $wallet ? (float) $wallet->balance : 0.0;
    }

    /**
     * Ajoute ou soustrait un montant du solde de l'utilisateur de façon atomique.
     *
     * Le verrou `lockForUpdate` empêche les doubles dépenses si plusieurs requêtes
     * arrivent simultanément pour le même portefeuille.
     *
     * @param  int    $userId  Identifiant de l'utilisateur
     * @param  int    $type    WalletHelper::ADD (1) ou WalletHelper::SUBTRACT (0)
     * @param  float  $amount  Montant positif à appliquer
     * @return float           Nouveau solde après l'opération
     *
     * @throws \InvalidArgumentException  Si le type est invalide ou le montant ≤ 0
     * @throws \RuntimeException          Si le portefeuille est introuvable
     *                                    ou si le solde est insuffisant (soustraction)
     */
    public static function updateBalance(int $userId, int $type, float $amount): float
    {
        if ($type !== self::ADD && $type !== self::SUBTRACT) {
            throw new \InvalidArgumentException('Le type doit être WalletHelper::ADD (1) ou WalletHelper::SUBTRACT (0).');
        }

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Le montant doit être supérieur à 0.');
        }

        return DB::transaction(function () use ($userId, $type, $amount) {
            $wallet = Wallet::where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if (! $wallet) {
                throw new \RuntimeException("Portefeuille introuvable pour l'utilisateur #{$userId}.");
            }

            if ($type === self::SUBTRACT) {
                if ((float) $wallet->balance < $amount) {
                    throw new \RuntimeException('Solde insuffisant pour effectuer cette opération.');
                }
                $wallet->decrement('balance', $amount);
            } else {
                $wallet->increment('balance', $amount);
            }

            // Recharger depuis la BDD pour retourner la valeur fraîche
            return (float) $wallet->fresh()->balance;
        });
    }
}
