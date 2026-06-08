<?php

namespace App\Helpers;

use App\Models\User;

class KycLimitHelper
{
    public static function getWithdrawLimit(User $user): array
    {
        return match ((int) $user->kyc_level) {
            0 => ['daily' => 500, 'monthly' => 2000],
            1 => ['daily' => 5000, 'monthly' => 50000],
            2 => ['daily' => 50000, 'monthly' => 500000],
            default => ['daily' => 500, 'monthly' => 2000],
        };
    }

    public static function canWithdraw(User $user, float $amount): bool
    {
        $limits = self::getWithdrawLimit($user);
        return $amount <= $limits['daily'];
    }

    public static function kycProgress(User $user): array
    {
        $docs = $user->kycDocuments;
        $total = 3;
        $completed = $docs->where('status', 'verified')->count();
        $percent = min(100, round(($completed / $total) * 100));

        return [
            'level' => $user->kyc_level,
            'status' => $user->kyc_status,
            'percent' => $percent,
            'completed_steps' => $completed,
            'total_steps' => $total,
            'documents' => $docs,
        ];
    }
}
