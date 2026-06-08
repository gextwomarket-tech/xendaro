<?php

namespace App\Helpers;

use App\Models\Instrument;

class PriceService
{
    private static array $bases = [
        'EURUSD' => 1.08450,
        'GBPUSD' => 1.26480,
        'USDJPY' => 150.200,
        'BTCUSD' => 64500.00,
        'ETHUSD' => 3350.00,
        'US30'   => 38500.00,
        'US100'  => 16800.00,
        'XAUUSD' => 2320.50,
        'XTIUSD' => 78.50,
    ];

    private static array $volatilities = [
        'forex'      => 0.0003,
        'crypto'     => 0.008,
        'indices'    => 0.003,
        'commodities'=> 0.005,
    ];

    public static function simulatePrice(Instrument $instrument): array
    {
        $base = self::$bases[$instrument->symbol] ?? ($instrument->bid ?? 100.0);
        $vol  = self::$volatilities[$instrument->category] ?? 0.003;

        $change = (mt_rand(-100, 100) / 100) * $vol;
        $price  = $base * (1 + $change);
        $spread = $price * 0.0002;

        $bid = round($price - $spread / 2, 5);
        $ask = round($price + $spread / 2, 5);

        return [
            'symbol'    => $instrument->symbol,
            'bid'       => $bid,
            'ask'       => $ask,
            'spread'    => round($spread, 5),
            'change_24h' => round((mt_rand(-500, 500) / 1000) * $base, 2),
            'change_24h_percent' => round((mt_rand(-500, 500) / 10000), 4),
            'high_24h'  => round($price * 1.02, 5),
            'low_24h'   => round($price * 0.98, 5),
            'volume_24h'=> mt_rand(100000, 50000000),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    public static function updateInstrumentPrices(): array
    {
        $prices = [];
        foreach (Instrument::where('is_active', true)->cursor() as $inst) {
            $data = self::simulatePrice($inst);
            $inst->update([
                'bid' => $data['bid'],
                'ask' => $data['ask'],
                'spread' => $data['spread'],
                'change_24h' => $data['change_24h'],
                'change_24h_percent' => $data['change_24h_percent'],
            ]);
            $prices[$inst->symbol] = $data;
        }
        return $prices;
    }

    public static function getAllPrices(): array
    {
        return Instrument::where('is_active', true)
            ->get(['symbol', 'name', 'category', 'bid', 'ask', 'spread', 'change_24h', 'change_24h_percent'])
            ->mapWithKeys(fn ($i) => [
                $i->symbol => [
                    'id' => $i->id,
                    'symbol' => $i->symbol,
                    'name' => $i->name,
                    'category' => $i->category,
                    'bid' => $i->bid,
                    'ask' => $i->ask,
                    'spread' => $i->spread,
                    'change_24h' => $i->change_24h,
                    'change_24h_percent' => $i->change_24h_percent,
                ]
            ])
            ->toArray();
    }

    public static function getMidPrice(Instrument|string $instrument): float
    {
        if (is_string($instrument)) {
            $instrument = Instrument::where('symbol', $instrument)->first();
        }
        if (! $instrument) return 0;
        return round(($instrument->bid + $instrument->ask) / 2, 5);
    }
}
