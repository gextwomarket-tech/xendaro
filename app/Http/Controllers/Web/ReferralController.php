<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $user = $request->user();

        // Générer un code parrainage si absent
        if (!$user->referral_code) {
            $user->update(['referral_code' => strtoupper(Str::random(8))]);
        }

        $referralUrl = route('auth.register') . '?ref=' . $user->referral_code;

        // Statistiques filleuls
        $referees = User::where('referred_by', $user->referral_code)
            ->select('id', 'first_name', 'last_name', 'created_at', 'status')
            ->orderByDesc('created_at')
            ->paginate(10);

        $totalReferees  = User::where('referred_by', $user->referral_code)->count();
        $activeReferees = User::where('referred_by', $user->referral_code)->where('status', 'active')->count();
        $pendingReferees = User::where('referred_by', $user->referral_code)->where('status', 'pending')->count();

        // Commissions — depuis la table referral_commissions si elle existe, sinon 0
        $totalCommissions  = 0;
        $availableCommissions = 0;
        $commissionHistory = collect();

        if (\Schema::hasTable('referral_commissions')) {
            $totalCommissions     = \DB::table('referral_commissions')->where('referrer_id', $user->id)->sum('amount');
            $availableCommissions = \DB::table('referral_commissions')->where('referrer_id', $user->id)->where('status', 'pending')->sum('amount');
            $commissionHistory    = Referral::where('referrer_id', $user->id)
                ->with('referee')
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();
        }

        // Graphique mensuel des commissions (12 derniers mois)
        $commissionChart = collect();
        if (\Schema::hasTable('referral_commissions')) {
            $commissionChart = \DB::table('referral_commissions')
                ->where('referrer_id', $user->id)
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return view('dashboard.referral', compact(
            'user', 'referralUrl',
            'referees', 'totalReferees', 'activeReferees', 'pendingReferees',
            'totalCommissions', 'availableCommissions', 'commissionHistory', 'commissionChart'
        ));
    }

    public function info(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'referral_code' => $user->referral_code,
            'referral_url'  => route('auth.register') . '?ref=' . $user->referral_code,
            'total_referees' => User::where('referred_by', $user->referral_code)->count(),
        ]);
    }

    public function referees(Request $request): \Illuminate\Http\JsonResponse
    {
        $referees = User::where('referred_by', $request->user()->referral_code)
            ->select('id', 'first_name', 'created_at', 'status')
            ->orderByDesc('created_at')
            ->paginate(20);
        return response()->json($referees);
    }

    public function commissions(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!\Schema::hasTable('referrals')) {
            return response()->json(['data' => []]);
        }
        $commissions = Referral::where('referrer_id', $request->user()->id)
            ->orderByDesc('created_at')->paginate(20);
        return response()->json($commissions);
    }

    public function withdraw(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['amount' => 'required|numeric|min:10']);
        $user = $request->user();

        if (!\Schema::hasTable('referrals')) {
            return back()->with('error', 'Système de parrainage non configuré.');
        }

        $available = Referral::where('referrer_id', $user->id)
            ->where('status', 'pending')
            ->sum('commission_amount');

        if ($request->amount > $available) {
            return back()->with('error', 'Montant supérieur aux commissions disponibles.');
        }

        // Transférer vers le wallet
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0, 'demo_balance' => 10000]);
        $wallet->increment('balance', $request->amount);

        // Marquer les commissions comme payées
        Referral::where('referrer_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'paid']);

        return back()->with('success', 'Commissions de $' . number_format($request->amount, 2) . ' transférées vers votre wallet.');
    }
}
