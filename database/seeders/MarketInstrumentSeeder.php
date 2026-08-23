<?php

namespace Database\Seeders;

use App\Models\MarketInstrument;
use Illuminate\Database\Seeder;

class MarketInstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Symboles TradingView (symbole_provider_externe) permettant l'affichage reel
     * dans les graphs/pages de trade des lors que l'admin les saisit (voir xendaro-fox-plan.json).
     */
    public function run(): void
    {
        $instruments = [
            ['nom' => 'Euro / Dollar US', 'symbole_interne' => 'EURUSD', 'categorie' => 'forex', 'symbole_provider_externe' => 'FX:EURUSD', 'spread' => 0.00010, 'levier_max' => 500, 'prix_reference' => 1.0850],
            ['nom' => 'Livre Sterling / Dollar US', 'symbole_interne' => 'GBPUSD', 'categorie' => 'forex', 'symbole_provider_externe' => 'FX:GBPUSD', 'spread' => 0.00015, 'levier_max' => 500, 'prix_reference' => 1.2650],
            ['nom' => 'Dollar US / Yen Japonais', 'symbole_interne' => 'USDJPY', 'categorie' => 'forex', 'symbole_provider_externe' => 'FX:USDJPY', 'spread' => 0.012, 'levier_max' => 500, 'prix_reference' => 149.50],
            ['nom' => 'Dollar Australien / Dollar US', 'symbole_interne' => 'AUDUSD', 'categorie' => 'forex', 'symbole_provider_externe' => 'FX:AUDUSD', 'spread' => 0.00018, 'levier_max' => 500, 'prix_reference' => 0.6550],
            ['nom' => 'Dollar US / Franc Suisse', 'symbole_interne' => 'USDCHF', 'categorie' => 'forex', 'symbole_provider_externe' => 'FX:USDCHF', 'spread' => 0.00016, 'levier_max' => 500, 'prix_reference' => 0.8800],
            ['nom' => 'Bitcoin / Dollar US', 'symbole_interne' => 'BTCUSD', 'categorie' => 'crypto', 'symbole_provider_externe' => 'BINANCE:BTCUSDT', 'spread' => 15, 'levier_max' => 20, 'prix_reference' => 62000],
            ['nom' => 'Ethereum / Dollar US', 'symbole_interne' => 'ETHUSD', 'categorie' => 'crypto', 'symbole_provider_externe' => 'BINANCE:ETHUSDT', 'spread' => 2.5, 'levier_max' => 20, 'prix_reference' => 3400],
            ['nom' => 'Solana / Dollar US', 'symbole_interne' => 'SOLUSD', 'categorie' => 'crypto', 'symbole_provider_externe' => 'BINANCE:SOLUSDT', 'spread' => 0.05, 'levier_max' => 10, 'prix_reference' => 145],
            ['nom' => 'XRP / Dollar US', 'symbole_interne' => 'XRPUSD', 'categorie' => 'crypto', 'symbole_provider_externe' => 'BINANCE:XRPUSDT', 'spread' => 0.001, 'levier_max' => 10, 'prix_reference' => 0.62],
            ['nom' => 'Or', 'symbole_interne' => 'XAUUSD', 'categorie' => 'metal', 'symbole_provider_externe' => 'OANDA:XAUUSD', 'spread' => 0.25, 'levier_max' => 200, 'prix_reference' => 2350],
            ['nom' => 'Argent', 'symbole_interne' => 'XAGUSD', 'categorie' => 'metal', 'symbole_provider_externe' => 'OANDA:XAGUSD', 'spread' => 0.02, 'levier_max' => 200, 'prix_reference' => 27.50],
            ['nom' => 'Pétrole Brut WTI', 'symbole_interne' => 'WTIUSD', 'categorie' => 'commodite', 'symbole_provider_externe' => 'TVC:USOIL', 'spread' => 0.03, 'levier_max' => 100, 'prix_reference' => 78.20],
            ['nom' => 'S&P 500', 'symbole_interne' => 'US500', 'categorie' => 'indice', 'symbole_provider_externe' => 'TVC:SPX', 'spread' => 0.4, 'levier_max' => 100, 'prix_reference' => 5200],
            ['nom' => 'Nasdaq 100', 'symbole_interne' => 'US100', 'categorie' => 'indice', 'symbole_provider_externe' => 'TVC:NDQ', 'spread' => 1.0, 'levier_max' => 100, 'prix_reference' => 18200],
            ['nom' => 'Apple Inc.', 'symbole_interne' => 'AAPL', 'categorie' => 'action', 'symbole_provider_externe' => 'NASDAQ:AAPL', 'spread' => 0.02, 'levier_max' => 20, 'prix_reference' => 190],
        ];

        foreach ($instruments as $instrument) {
            MarketInstrument::updateOrCreate(
                ['symbole_interne' => $instrument['symbole_interne']],
                $instrument + ['provider' => 'tradingview', 'est_actif' => true]
            );
        }
    }
}
