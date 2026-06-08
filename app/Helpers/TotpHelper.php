<?php

namespace App\Helpers;

class TotpHelper
{
    public static function generateSecret(int $length = 32): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }

    public static function getUri(string $secret, string $label, string $issuer = 'MoonTrade'): string
    {
        return 'otpauth://totp/'.rawurlencode($issuer).':'.rawurlencode($label)
            .'?secret='.$secret.'&issuer='.rawurlencode($issuer);
    }

    public static function generateCode(string $secret, ?int $timeSlice = null): string
    {
        $timeSlice ??= floor(time() / 30);
        $secretKey = self::base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hm = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord($hm[19]) & 0x0F;
        $code = (
            ((ord($hm[$offset]) & 0x7F) << 24) |
            ((ord($hm[$offset + 1]) & 0xFF) << 16) |
            ((ord($hm[$offset + 2]) & 0xFF) << 8) |
            (ord($hm[$offset + 3]) & 0xFF)
        ) % 1000000;
        return str_pad((string)$code, 6, '0', STR_PAD_LEFT);
    }

    public static function verifyCode(string $secret, string $code, int $window = 1): bool
    {
        $currentSlice = floor(time() / 30);
        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals(self::generateCode($secret, $currentSlice + $i), $code)) {
                return true;
            }
        }
        return false;
    }

    public static function base32Decode(string $input): string
    {
        $map = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper(str_replace('=', '', $input));
        $binary = '';
        foreach (str_split($input) as $char) {
            $val = strpos($map, $char);
            if ($val === false) continue;
            $binary .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
        }
        $output = '';
        for ($i = 0; $i + 8 <= strlen($binary); $i += 8) {
            $output .= chr(bindec(substr($binary, $i, 8)));
        }
        return $output;
    }

    public static function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = implode('-', str_split(strtoupper(substr(md5(uniqid()), 0, 8)), 4));
        }
        return $codes;
    }
}
