<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format(float $amount, string $currency = 'USD'): string
    {
        $symbol = match ($currency) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            default => $currency . ' ',
        };

        return $symbol . number_format($amount, 2);
    }

    public static function round(float $amount, int $decimals = 2): float
    {
        return round($amount, $decimals);
    }

    public static function pipsToPrice(float $pips, string $symbol): float
    {
        return match (true) {
            str_contains($symbol, 'JPY') => $pips * 0.01,
            default => $pips * 0.0001,
        };
    }
}
