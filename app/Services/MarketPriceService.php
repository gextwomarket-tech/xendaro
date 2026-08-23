<?php

namespace App\Services;

use App\Models\MarketInstrument;

/**
 * MVP - Service de simulation de prix "live" pour la page Trade (xendaro-fox-plan.json > Page id 37).
 *
 * Il n'y a, a ce stade du MVP, aucun flux de marche reel branche (voir
 * xendaro-fox-plan.json > Technologies.temps_reel: "prevu des le MVP en mode simule si
 * pas de flux reel disponible"). Ce service calcule un prix "vivant" a partir de
 * MarketInstrument::prix_reference en y appliquant une legere fluctuation
 * pseudo-aleatoire deterministe (+/- 0.05% par defaut).
 *
 * Le calcul est "seede" par symbole + seconde courante (hash CRC32, pas de mutation de
 * l'etat aleatoire global PHP via mt_srand) : le prix reste donc stable pendant la meme
 * seconde (utile pour que plusieurs composants affichant le meme instrument au meme
 * instant restent coherents) et varie ensuite d'un appel a l'autre, ce qui suffit a donner
 * une impression de flux "live" cote UI (watchlist, ticker, chart, calcul de P&L) sans
 * dependance externe ni cle API.
 *
 * Facilement remplacable plus tard par un vrai flux (websocket broker, API tierce) en
 * gardant la meme interface publique (currentPrice / bidAsk).
 */
class MarketPriceService
{
    /**
     * Amplitude maximale de fluctuation appliquee au prix de reference (0.05%).
     */
    private const FLUCTUATION_MAX = 0.0005;

    /**
     * Prix "mid" courant simule de l'instrument (base pour le calcul de marge et de P&L).
     */
    public static function currentPrice(MarketInstrument $instrument): float
    {
        $base = (float) $instrument->prix_reference;

        if ($base <= 0) {
            return 0.0;
        }

        $variation = self::deterministicVariation($instrument->symbole_interne);

        return round($base * (1 + $variation), 5);
    }

    /**
     * Bid/Ask/Spread simules pour le price_ticker et la watchlist.
     *
     * @return array{bid: float, ask: float, mid: float, spread: float}
     */
    public static function bidAsk(MarketInstrument $instrument): array
    {
        $mid = self::currentPrice($instrument);
        $spread = (float) $instrument->spread;

        return [
            'bid' => round($mid - ($spread / 2), 5),
            'ask' => round($mid + ($spread / 2), 5),
            'mid' => $mid,
            'spread' => $spread,
        ];
    }

    /**
     * Variation pseudo-aleatoire deterministe dans [-FLUCTUATION_MAX, +FLUCTUATION_MAX],
     * "seedee" par symbole + seconde Unix courante. N'utilise pas mt_srand() afin de ne
     * jamais perturber l'etat aleatoire global du processus PHP (important pour ne pas
     * impacter d'autres tirages aleatoires realises pendant la meme requete, ex: Str::random).
     */
    private static function deterministicVariation(string $seedKey): float
    {
        $hash = crc32($seedKey.'|'.now()->timestamp);

        // Ramene le hash (0..4294967295) sur un intervalle [-1000, 1000] puis normalise en [-1, 1].
        $normalized = (($hash % 2001) - 1000) / 1000;

        return $normalized * self::FLUCTUATION_MAX;
    }
}
