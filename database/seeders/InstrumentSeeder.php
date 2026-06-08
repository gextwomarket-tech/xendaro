<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing instruments without truncating (to avoid FK constraint issues)
        Instrument::query()->delete();

        $instruments = [
            // Cryptocurrencies - matching trade.html symbols with slashes
            ['symbol' => 'BTC/USD', 'name' => 'Bitcoin', 'category' => 'crypto', 'bid' => 64500.00, 'ask' => 64600.00, 'specs' => ['leverage' => 100, 'contract_size' => 1, 'decimals' => 0]],
            ['symbol' => 'ETH/USD', 'name' => 'Ethereum', 'category' => 'crypto', 'bid' => 3350.00, 'ask' => 3360.00, 'specs' => ['leverage' => 100, 'contract_size' => 1, 'decimals' => 2]],
            ['symbol' => 'BNB/USD', 'name' => 'BNB', 'category' => 'crypto', 'bid' => 615.00, 'ask' => 620.00, 'specs' => ['leverage' => 50, 'contract_size' => 1, 'decimals' => 2]],
            ['symbol' => 'SOL/USD', 'name' => 'Solana', 'category' => 'crypto', 'bid' => 210.00, 'ask' => 215.00, 'specs' => ['leverage' => 50, 'contract_size' => 1, 'decimals' => 3]],
            ['symbol' => 'XRP/USD', 'name' => 'Ripple', 'category' => 'crypto', 'bid' => 2.45, 'ask' => 2.50, 'specs' => ['leverage' => 50, 'contract_size' => 100, 'decimals' => 5]],
            ['symbol' => 'DOGE/USD', 'name' => 'Dogecoin', 'category' => 'crypto', 'bid' => 0.38, 'ask' => 0.39, 'specs' => ['leverage' => 20, 'contract_size' => 1000, 'decimals' => 5]],

            // Forex
            ['symbol' => 'EUR/USD', 'name' => 'Euro / USD', 'category' => 'forex', 'bid' => 1.08450, 'ask' => 1.08465, 'specs' => ['leverage' => 100, 'contract_size' => 100000, 'decimals' => 5]],
            ['symbol' => 'GBP/USD', 'name' => 'British Pound / USD', 'category' => 'forex', 'bid' => 1.26480, 'ask' => 1.26495, 'specs' => ['leverage' => 100, 'contract_size' => 100000, 'decimals' => 5]],
            ['symbol' => 'USD/JPY', 'name' => 'US Dollar / Yen', 'category' => 'forex', 'bid' => 150.200, 'ask' => 150.220, 'specs' => ['leverage' => 100, 'contract_size' => 100000, 'decimals' => 3]],

            // Commodities
            ['symbol' => 'XAU/USD', 'name' => 'Gold / USD', 'category' => 'commodities', 'bid' => 2320.50, 'ask' => 2321.00, 'specs' => ['leverage' => 100, 'contract_size' => 100, 'decimals' => 2]],
        ];

        foreach ($instruments as $inst) {
            $inst['spread'] = round($inst['ask'] - $inst['bid'], 8);
            $inst['change_24h'] = round((random_int(-100, 100) / 1000) * $inst['bid'], 2);
            $inst['change_24h_percent'] = round(($inst['change_24h'] / $inst['bid']) * 100, 4);
            Instrument::create($inst);
        }
    }
}
