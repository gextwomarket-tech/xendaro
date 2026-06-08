<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralCommission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    public function info(Request $request): JsonResponse
    {
        $user = $request->user();
        $referrals = Referral::where('referrer_id', $user->id)->get();

        return ApiResponse::success([
            'referral_code' => $user->referral_code,
            'referral_link' => url('/register?ref=' . $user->referral_code),
            'total_referees' => $referrals->count(),
            'active_referees' => $referrals->where('is_active', true)->count(),
            'pending_referees' => $referrals->where('is_active', false)->count(),
            'total_commissions' => $referrals->sum('total_commission'),
            'available_commissions' => ReferralCommission::where('referrer_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount'),
        ]);
    }

    public function referees(Request $request): JsonResponse
    {
        $referees = Referral::where('referrer_id', $request->user()->id)
            ->with('referee:id,first_name,last_name,email,created_at')
            ->latest()
            ->paginate($request->integer('per_page', 10));

        $data = $referees->through(function ($referral) {
            return [
                'id' => $referral->id,
                'name' => $referral->referee?->first_name ? $referral->referee->first_name . ' ' . strtoupper(substr($referral->referee->last_name ?? '', 0, 1)) . '***' : 'Anonyme',
                'registered_at' => $referral->referee?->created_at,
                'is_active' => $referral->is_active,
                'converted_at' => $referral->converted_at,
                'commission' => $referral->total_commission,
            ];
        });

        return ApiResponse::paginated($data);
    }

    public function commissions(Request $request): JsonResponse
    {
        $commissions = ReferralCommission::where('referrer_id', $request->user()->id)
            ->with('referral.referee:id,first_name,last_name')
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return ApiResponse::paginated($commissions);
    }

    public function withdraw(Request $request): JsonResponse
    {
        $user = $request->user();
        $amount = ReferralCommission::where('referrer_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        if ($amount <= 0) {
            return ApiResponse::error('Aucune commission disponible', 422);
        }

        DB::transaction(function () use ($user, $amount) {
            ReferralCommission::where('referrer_id', $user->id)
                ->where('status', 'pending')
                ->update(['status' => 'paid', 'paid_at' => now()]);

            $wallet = $user->wallet;
            if ($wallet) {
                $wallet->increment('balance', $amount);
            }
        });

        return ApiResponse::success(null, 'Commissions versées sur votre wallet');
    }
}
