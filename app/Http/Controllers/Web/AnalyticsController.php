<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Defense in depth: Verify KYC status (middleware already does this, but safety check)
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez compléter votre vérification KYC pour accéder à vos analyses.');
        }

        $period = $request->get('period', '30');

        // ── Plage de dates ────────────────────────────────────────────
        $dateFrom = match($period) {
            '7'    => now()->subDays(7),
            '30'   => now()->subDays(30),
            '90'   => now()->subDays(90),
            '180'  => now()->subDays(180),
            '365'  => now()->subDays(365),
            default => null, // tout
        };

        $query = fn() => $user->trades()
            ->where('status', 'closed')
            ->when($dateFrom, fn($q) => $q->where('closed_at', '>=', $dateFrom));

        // ── Métriques de base ─────────────────────────────────────────
        $total       = (clone $query())->count();
        $wins        = (clone $query())->where('profit_loss', '>=', 0)->count();
        $losses      = $total - $wins;
        $winRate     = $total > 0 ? round($wins / $total * 100, 1) : 0;

        $grossProfit = (clone $query())->where('profit_loss', '>=', 0)->sum('profit_loss');
        $grossLoss   = abs((clone $query())->where('profit_loss', '<', 0)->sum('profit_loss'));
        $profitFactor = $grossLoss > 0 ? round($grossProfit / $grossLoss, 2) : ($grossProfit > 0 ? '∞' : 0);

        $avgDuration = (clone $query())->avg('duration_seconds');
        $topAsset    = (clone $query())
            ->with('instrument')
            ->select('instrument_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('instrument_id')
            ->orderByDesc('cnt')
            ->first();

        // ── Drawdown maximum ──────────────────────────────────────────
        $equity = 0; $peak = 0; $maxDrawdown = 0;
        $closedTrades = (clone $query())->orderBy('closed_at')->pluck('profit_loss');
        foreach ($closedTrades as $pnl) {
            $equity += (float)$pnl;
            if ($equity > $peak) $peak = $equity;
            $dd = $peak > 0 ? (($peak - $equity) / $peak) * 100 : 0;
            if ($dd > $maxDrawdown) $maxDrawdown = $dd;
        }

        // ── Évolution du solde (par jour) ─────────────────────────────
        $balanceChart = (clone $query())
            ->orderBy('closed_at')
            ->select(DB::raw('DATE(closed_at) as day'), DB::raw('SUM(profit_loss) as daily_pnl'))
            ->groupBy('day')
            ->get()
            ->map(function ($row) use (&$equity) {
                static $cum = 0;
                $cum += (float)$row->daily_pnl;
                return ['date' => $row->day, 'pnl' => round($cum, 2)];
            });

        // ── Répartition par actif (top 5) ─────────────────────────────
        $byAsset = (clone $query())
            ->with('instrument')
            ->select('instrument_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('instrument_id')
            ->orderByDesc('cnt')
            ->limit(6)
            ->get()
            ->map(fn($r) => [
                'label' => $r->instrument?->symbol ?? 'Autre',
                'count' => $r->cnt,
            ]);

        // ── P&L moyen par jour de la semaine ─────────────────────────
        $byDow = (clone $query())
            ->select(DB::raw('DAYOFWEEK(closed_at) as dow'), DB::raw('AVG(profit_loss) as avg_pnl'))
            ->groupBy('dow')
            ->orderBy('dow')
            ->get()
            ->keyBy('dow');

        $dowLabels = ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'];
        $dowData   = [];
        for ($i = 1; $i <= 7; $i++) {
            $dowData[] = round((float)($byDow->get($i)?->avg_pnl ?? 0), 2);
        }

        // ── Heatmap : P&L moyen par heure × jour de semaine ──────────
        $heatmapRaw = (clone $query())
            ->select(
                DB::raw('DAYOFWEEK(closed_at) as dow'),
                DB::raw('HOUR(closed_at) as hour'),
                DB::raw('AVG(profit_loss) as avg_pnl')
            )
            ->groupBy('dow', 'hour')
            ->get();

        $heatmap = [];
        foreach ($heatmapRaw as $row) {
            $heatmap[$row->dow][$row->hour] = round((float)$row->avg_pnl, 2);
        }

        // ── Scatter : durée vs P&L ────────────────────────────────────
        $scatter = (clone $query())
            ->whereNotNull('duration_seconds')
            ->select('duration_seconds', 'profit_loss')
            ->limit(200)
            ->get()
            ->map(fn($t) => ['x' => round($t->duration_seconds / 60), 'y' => round((float)$t->profit_loss, 2)]);

        return view('dashboard.analytics', compact(
            'period', 'total', 'wins', 'losses', 'winRate',
            'grossProfit', 'grossLoss', 'profitFactor', 'avgDuration',
            'topAsset', 'maxDrawdown', 'balanceChart', 'byAsset',
            'dowLabels', 'dowData', 'heatmap', 'scatter'
        ));
    }

    public function performance(Request $request)
    {
        return $this->index($request);
    }
}
