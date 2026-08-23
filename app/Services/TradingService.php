<?php

namespace App\Services;

use App\Models\MarketInstrument;
use App\Models\TradeHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Logique metier d'ouverture/cloture de position pour la page Trade
 * (xendaro-fox-plan.json > Page id 37 "trade" - coeur du projet).
 *
 * ============================================================================
 * DECISION DE MODELISATION "MARGE / SOLDE" (a lire avant de modifier ce fichier)
 * ============================================================================
 * A L'OUVERTURE d'une position, le solde brut du wallet (solde_reel / solde_demo) N'EST
 * JAMAIS TOUCHE. On calcule une marge requise et on verifie seulement qu'elle est
 * inferieure a la "marge libre" du compte actif (solde - somme des marge_utilisee des
 * positions deja ouvertes sur le meme mode). La marge est ensuite simplement enregistree
 * dans trade_history.marge_utilisee : elle est "tracee" (pour l'affichage de
 * AccountSummary : marge utilisee / marge libre / niveau de marge), pas "bloquee
 * physiquement" en decrementant le wallet.
 *
 * A LA CLOTURE d'une position, on met a jour la ligne trade_history (prix_cloture,
 * profit_perte via TradeHistory::calculerProfitFlottant(), statut='cloture',
 * cloture_le=now()) puis on credite/debite le solde du wallet actif du montant exact de
 * profit_perte (gain => increment, perte => decrement). Comme la marge n'a jamais ete
 * soustraite a l'ouverture, il n'y a rien a "restituer" a la cloture : une seule ecriture
 * comptable par cycle de vie complet d'un trade.
 *
 * Pourquoi ce choix plutot que de bloquer/debloquer la marge au wallet ?
 * - C'est le modele le plus simple et le moins sujet aux bugs pour un MVP (pas de risque
 *   d'oublier de "rendre" la marge, pas de risque de double-decompte).
 * - Le solde affiche au client reste toujours "l'argent reellement disponible avant P&L",
 *   ce qui correspond a la notion MT5 de "Balance" (par opposition a "Equity" qui, elle,
 *   integre le P&L flottant des positions ouvertes et EST recalculee a la volee dans
 *   AccountSummaryWidget : equite = solde + somme(P&L flottant), marge libre = equite -
 *   marge utilisee, niveau de marge = equite / marge utilisee * 100).
 *
 * ============================================================================
 * FORMULE DE MARGE (approximation MVP)
 * ============================================================================
 *   marge = (volume * prix_actuel * CONTRACT_SIZE) / levier_max
 *
 * CONTRACT_SIZE = 100 (au lieu des 100 000 unites d'un "vrai" lot standard forex) afin
 * que les montants de marge restent lisibles et coherents avec le solde demo par defaut
 * de 10 000$ (User::booted()). Ajustable plus tard via la constante CONTRACT_SIZE si le
 * client souhaite un dimensionnement de lot "reglementaire".
 *
 * ============================================================================
 * ORDRES EN ATTENTE (buy_limit / sell_limit / buy_stop / sell_stop)
 * ============================================================================
 * Le schema trade_histories ne comporte que 2 statuts ('ouvert' / 'cloture'), il n'existe
 * pas de statut "en_attente"/"pending". Pour ce MVP, quel que soit le type_ordre choisi
 * dans le formulaire, la position est donc immediatement ouverte au prix courant simule
 * (comme un ordre "marche") - le type_ordre est conserve tel quel a titre informatif/UI
 * uniquement. Un vrai moteur d'execution d'ordres en attente (job planifie verifiant le
 * franchissement du prix declencheur) est hors scope MVP et pourra etre ajoute plus tard
 * sans casser ce contrat de service.
 */
class TradingService
{
    /**
     * Taille de contrat simplifiee utilisee pour le calcul de marge (voir doc de classe).
     */
    private const CONTRACT_SIZE = 100;

    /**
     * Calcule la marge requise pour ouvrir (ou visualiser en direct dans le formulaire)
     * une position sur un instrument donne, pour un volume donne.
     */
    public static function calculerMarge(MarketInstrument $instrument, float $volume, ?float $prix = null): float
    {
        $prix ??= MarketPriceService::currentPrice($instrument);
        $levier = max(1, (int) $instrument->levier_max);

        return round(($volume * $prix * self::CONTRACT_SIZE) / $levier, 2);
    }

    /**
     * Somme des marges des positions actuellement ouvertes pour un utilisateur/mode donne.
     */
    public static function margeUtiliseePour(User $user, string $mode): float
    {
        return (float) TradeHistory::query()
            ->where('user_id', $user->id)
            ->where('mode', $mode)
            ->where('statut', 'ouvert')
            ->sum('marge_utilisee');
    }

    /**
     * Marge libre = solde du wallet actif - marge deja utilisee sur les positions ouvertes.
     */
    public static function margeLibrePour(User $user, string $mode): float
    {
        $wallet = $user->wallet;
        $solde = $wallet ? $wallet->soldePour($mode) : 0.0;

        return $solde - self::margeUtiliseePour($user, $mode);
    }

    /**
     * Ouvre une position: cree la ligne trade_history (statut='ouvert') apres verification
     * de la marge libre disponible. Ne touche jamais au solde brut du wallet (voir doc de
     * classe ci-dessus).
     *
     * @throws RuntimeException si l'instrument est inactif, le volume invalide, ou la marge
     *                          libre insuffisante.
     */
    public static function openPosition(
        User $user,
        MarketInstrument $instrument,
        string $mode,
        string $sens,
        float $volume,
        ?float $stopLoss = null,
        ?float $takeProfit = null,
        string $typeOrdre = 'marche'
    ): TradeHistory {
        if (! $instrument->est_actif) {
            throw new RuntimeException(__('app.trade.errors.instrument_inactive'));
        }

        if ($volume <= 0) {
            throw new RuntimeException(__('app.trade.errors.invalid_volume'));
        }

        if (! in_array($mode, ['demo', 'reel'], true)) {
            throw new RuntimeException(__('app.trade.errors.invalid_mode'));
        }

        if (! in_array($sens, ['buy', 'sell'], true)) {
            throw new RuntimeException(__('app.trade.errors.invalid_sens'));
        }

        $prixOuverture = MarketPriceService::currentPrice($instrument);

        if ($prixOuverture <= 0) {
            throw new RuntimeException(__('app.trade.errors.invalid_price'));
        }

        $marge = self::calculerMarge($instrument, $volume, $prixOuverture);

        return DB::transaction(function () use ($user, $instrument, $mode, $sens, $volume, $stopLoss, $takeProfit, $typeOrdre, $prixOuverture, $marge) {
            $margeLibre = self::margeLibrePour($user, $mode);

            if ($marge > $margeLibre) {
                throw new RuntimeException(__('app.trade.errors.insufficient_margin'));
            }

            return TradeHistory::create([
                'user_id' => $user->id,
                'market_instrument_id' => $instrument->id,
                'mode' => $mode,
                'sens' => $sens,
                'type_ordre' => $typeOrdre,
                'volume' => $volume,
                'prix_ouverture' => $prixOuverture,
                'stop_loss' => $stopLoss,
                'take_profit' => $takeProfit,
                'marge_utilisee' => $marge,
                'statut' => 'ouvert',
                'ouvert_le' => now(),
            ]);
        });
    }

    /**
     * Cloture une position: met a jour prix_cloture / profit_perte / statut / cloture_le,
     * puis credite/debite le wallet actif du montant exact de profit_perte.
     * Idempotent: si la position est deja cloturee, elle est simplement retournee telle quelle.
     */
    public static function closePosition(TradeHistory $trade, ?float $prixCloture = null): TradeHistory
    {
        if ($trade->statut === 'cloture') {
            return $trade;
        }

        $trade->loadMissing('instrument', 'user.wallet');

        $prixCloture ??= MarketPriceService::currentPrice($trade->instrument);
        $profitPerte = $trade->calculerProfitFlottant($prixCloture);

        return DB::transaction(function () use ($trade, $prixCloture, $profitPerte) {
            $trade->update([
                'prix_cloture' => $prixCloture,
                'profit_perte' => $profitPerte,
                'statut' => 'cloture',
                'cloture_le' => now(),
            ]);

            $wallet = $trade->user->wallet;

            if ($wallet) {
                $colonne = $trade->mode === 'reel' ? 'solde_reel' : 'solde_demo';

                if ($profitPerte >= 0) {
                    $wallet->increment($colonne, $profitPerte);
                } else {
                    $wallet->decrement($colonne, abs($profitPerte));
                }
            }

            return $trade->fresh(['instrument', 'user']);
        });
    }
}
