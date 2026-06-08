<?php

namespace App\Helpers;

class StringHelper
{
    public static function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);
        $first = substr($local, 0, 1);
        $masked = str_repeat('*', max(0, strlen($local) - 1));
        return $first . $masked . '@' . $domain;
    }

    public static function generateReference(string $prefix = 'TXN'): string
    {
        return $prefix . '-' . strtoupper(uniqid()) . '-' . random_int(1000, 9999);
    }

    public static function humanDuration(?int $seconds): string
    {
        if (! $seconds) return '0s';
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        $parts = [];
        if ($hours) $parts[] = $hours . 'h';
        if ($minutes) $parts[] = $minutes . 'm';
        if ($secs) $parts[] = $secs . 's';
        return implode(' ', $parts);
    }
}
