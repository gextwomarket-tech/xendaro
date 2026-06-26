<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        Instrument::query()->delete();

        $instruments = [
            // ── CRYPTO (14) ──────────────────────────────────────────────
            ['symbol'=>'BTC/USD',  'name'=>'Bitcoin',    'category'=>'crypto', 'bid'=>64500.00, 'ask'=>64600.00, 'specs'=>['leverage'=>100,'contract_size'=>1,     'decimals'=>0]],
            ['symbol'=>'ETH/USD',  'name'=>'Ethereum',   'category'=>'crypto', 'bid'=>3350.00,  'ask'=>3360.00,  'specs'=>['leverage'=>100,'contract_size'=>1,     'decimals'=>2]],
            ['symbol'=>'BNB/USD',  'name'=>'BNB',        'category'=>'crypto', 'bid'=>615.00,   'ask'=>620.00,   'specs'=>['leverage'=>50, 'contract_size'=>1,     'decimals'=>2]],
            ['symbol'=>'SOL/USD',  'name'=>'Solana',     'category'=>'crypto', 'bid'=>210.00,   'ask'=>215.00,   'specs'=>['leverage'=>50, 'contract_size'=>1,     'decimals'=>3]],
            ['symbol'=>'XRP/USD',  'name'=>'Ripple',     'category'=>'crypto', 'bid'=>2.45,     'ask'=>2.50,     'specs'=>['leverage'=>50, 'contract_size'=>100,   'decimals'=>5]],
            ['symbol'=>'DOGE/USD', 'name'=>'Dogecoin',   'category'=>'crypto', 'bid'=>0.380,    'ask'=>0.390,    'specs'=>['leverage'=>20, 'contract_size'=>1000,  'decimals'=>5]],
            ['symbol'=>'ADA/USD',  'name'=>'Cardano',    'category'=>'crypto', 'bid'=>0.620,    'ask'=>0.625,    'specs'=>['leverage'=>20, 'contract_size'=>1000,  'decimals'=>5]],
            ['symbol'=>'AVAX/USD', 'name'=>'Avalanche',  'category'=>'crypto', 'bid'=>38.50,    'ask'=>38.80,    'specs'=>['leverage'=>50, 'contract_size'=>1,     'decimals'=>3]],
            ['symbol'=>'LINK/USD', 'name'=>'Chainlink',  'category'=>'crypto', 'bid'=>18.20,    'ask'=>18.30,    'specs'=>['leverage'=>50, 'contract_size'=>1,     'decimals'=>4]],
            ['symbol'=>'DOT/USD',  'name'=>'Polkadot',   'category'=>'crypto', 'bid'=>9.80,     'ask'=>9.85,     'specs'=>['leverage'=>20, 'contract_size'=>10,    'decimals'=>4]],
            ['symbol'=>'MATIC/USD','name'=>'Polygon',    'category'=>'crypto', 'bid'=>0.980,    'ask'=>0.985,    'specs'=>['leverage'=>20, 'contract_size'=>1000,  'decimals'=>5]],
            ['symbol'=>'LTC/USD',  'name'=>'Litecoin',   'category'=>'crypto', 'bid'=>95.00,    'ask'=>95.50,    'specs'=>['leverage'=>50, 'contract_size'=>1,     'decimals'=>2]],
            ['symbol'=>'UNI/USD',  'name'=>'Uniswap',    'category'=>'crypto', 'bid'=>12.50,    'ask'=>12.55,    'specs'=>['leverage'=>20, 'contract_size'=>10,    'decimals'=>4]],
            ['symbol'=>'ATOM/USD', 'name'=>'Cosmos',     'category'=>'crypto', 'bid'=>10.20,    'ask'=>10.25,    'specs'=>['leverage'=>20, 'contract_size'=>10,    'decimals'=>4]],

            // ── FOREX (10) ───────────────────────────────────────────────
            ['symbol'=>'EUR/USD', 'name'=>'Euro / USD',          'category'=>'forex', 'bid'=>1.08450, 'ask'=>1.08465, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>5]],
            ['symbol'=>'GBP/USD', 'name'=>'Pound / USD',         'category'=>'forex', 'bid'=>1.26480, 'ask'=>1.26495, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>5]],
            ['symbol'=>'USD/JPY', 'name'=>'USD / Yen',           'category'=>'forex', 'bid'=>150.200, 'ask'=>150.220, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>3]],
            ['symbol'=>'USD/CHF', 'name'=>'USD / Swiss Franc',   'category'=>'forex', 'bid'=>0.90120, 'ask'=>0.90135, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>5]],
            ['symbol'=>'USD/CAD', 'name'=>'USD / Canada',        'category'=>'forex', 'bid'=>1.36250, 'ask'=>1.36265, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>5]],
            ['symbol'=>'AUD/USD', 'name'=>'Australia / USD',     'category'=>'forex', 'bid'=>0.65100, 'ask'=>0.65115, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>5]],
            ['symbol'=>'NZD/USD', 'name'=>'New Zealand / USD',   'category'=>'forex', 'bid'=>0.60800, 'ask'=>0.60815, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>5]],
            ['symbol'=>'EUR/GBP', 'name'=>'Euro / Pound',        'category'=>'forex', 'bid'=>0.85820, 'ask'=>0.85835, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>5]],
            ['symbol'=>'EUR/JPY', 'name'=>'Euro / Yen',          'category'=>'forex', 'bid'=>162.800, 'ask'=>162.825, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>3]],
            ['symbol'=>'GBP/JPY', 'name'=>'Pound / Yen',         'category'=>'forex', 'bid'=>190.200, 'ask'=>190.230, 'specs'=>['leverage'=>100,'contract_size'=>100000,'decimals'=>3]],

            // ── COMMODITIES (4) ──────────────────────────────────────────
            ['symbol'=>'XAU/USD', 'name'=>'Gold / USD',      'category'=>'commodities', 'bid'=>2320.50, 'ask'=>2321.00, 'specs'=>['leverage'=>100,'contract_size'=>100,  'decimals'=>2]],
            ['symbol'=>'XAG/USD', 'name'=>'Silver / USD',    'category'=>'commodities', 'bid'=>28.50,   'ask'=>28.55,   'specs'=>['leverage'=>100,'contract_size'=>5000, 'decimals'=>3]],
            ['symbol'=>'USOIL',   'name'=>'US Crude Oil',    'category'=>'commodities', 'bid'=>78.50,   'ask'=>78.60,   'specs'=>['leverage'=>100,'contract_size'=>1000, 'decimals'=>2]],
            ['symbol'=>'UKOIL',   'name'=>'UK Brent Oil',    'category'=>'commodities', 'bid'=>82.30,   'ask'=>82.40,   'specs'=>['leverage'=>100,'contract_size'=>1000, 'decimals'=>2]],

            // ── INDICES (3) ──────────────────────────────────────────────
            ['symbol'=>'US500',    'name'=>'S&P 500',      'category'=>'indices', 'bid'=>5200.00,  'ask'=>5201.00,  'specs'=>['leverage'=>100,'contract_size'=>1,'decimals'=>2]],
            ['symbol'=>'NASDAQ',   'name'=>'NASDAQ 100',   'category'=>'indices', 'bid'=>18200.00, 'ask'=>18205.00, 'specs'=>['leverage'=>100,'contract_size'=>1,'decimals'=>2]],
            ['symbol'=>'DOWJONES', 'name'=>'Dow Jones 30', 'category'=>'indices', 'bid'=>38500.00, 'ask'=>38505.00, 'specs'=>['leverage'=>100,'contract_size'=>1,'decimals'=>2]],
        ];

        foreach ($instruments as $inst) {
            $inst['spread'] = round($inst['ask'] - $inst['bid'], 8);
            $inst['change_24h'] = round((random_int(-100, 100) / 1000) * $inst['bid'], 2);
            $inst['change_24h_percent'] = round(($inst['change_24h'] / $inst['bid']) * 100, 4);
            Instrument::create($inst);
        }
    }
}
