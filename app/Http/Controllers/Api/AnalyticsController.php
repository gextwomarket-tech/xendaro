<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function performance(Request $request): JsonResponse
    {
        $days = match ($request->string('period', '30j')) {
            '7j' => 7,
            '30j' => 30,
            '3m' => 90,
            '6m' => 180,
            '1an' => 365,
            default => 30,
        };

        $from = now()->subDays($days);
        $user = $request->user();

        $trades = $user->trades()
            ->closed()
            ->where('closed_at', '>=', $from)
            ->get();

        $totalTrades = $trades->count();
        $winTrades = $trades->where('profit_loss', '>', 0)->count();
        $winRate = $totalTrades > 0 ? round(($winTrades / $totalTrades) * 100, 2) : 0;

        $totalProfit = $trades->where('profit_loss', '>', 0)->sum('profit_loss');
        $totalLoss = abs($trades->where('profit_loss', '<', 0)->sum('profit_loss'));
        $profitFactor = $totalLoss > 0 ? round($totalProfit / $totalLoss, 2) : null;

        $maxDrawdown = $this->calculateMaxDrawdown($trades);
        $avgDuration = $trades->avg('duration_seconds');

        $assetDistribution = $trades->groupBy('instrument_id')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(5);

        $weekdayPerformance = $user->trades()
            ->closed()
            ->where('closed_at', '>=', $from)
            ->select(DB::raw('strftime("%w", closed_at) as weekday'), DB::raw('AVG(profit_loss) as avg_pnl'), DB::raw('COUNT(*) as count'))
            ->groupBy('weekday')
            ->get()
            ->mapWithKeys(fn($row) => [
                $this->weekdayName($row->weekday) => [
                    'avg_pnl' => round($row->avg_pnl, 2),
                    'count' => $row->count,
                ]
            ]);

        $heatmap = $user->trades()
            ->closed()
            ->where('closed_at', '>=', $from)
            ->select(
                DB::raw('strftime("%w", closed_at) as weekday'),
                DB::raw('strftime("%H", closed_at) as hour'),
                DB::raw('AVG(profit_loss) as avg_pnl')
            )
            ->groupBy('weekday', 'hour')
            ->get()
            ->map(fn($row) => [
                'weekday' => $this->weekdayName($row->weekday),
                'hour' => (int) $row->hour,
                'avg_pnl' => round($row->avg_pnl, 2),
            ]);

        return ApiResponse::success([
            'period' => $request->string('period', '30j'),
            'total_trades' => $totalTrades,
            'win_rate' => $winRate,
            'profit_factor' => $profitFactor,
            'max_drawdown' => $maxDrawdown,
            'avg_duration_seconds' => round($avgDuration ?? 0, 0),
            'total_profit' => round($totalProfit, 2),
            'total_loss' => round($totalLoss, 2),
            'asset_distribution' => $assetDistribution,
            'weekday_performance' => $weekdayPerformance,
            'heatmap' => $heatmap,
        ]);
    }

    private function calculateMaxDrawdown($trades): float
    {
        $peak = 0;
        $maxDd = 0;
        $cum = 0;
        foreach ($trades->sortBy('closed_at') as $trade) {
            $cum += $trade->profit_loss;
            if ($cum > $peak) $peak = $cum;
            $dd = $peak - $cum;
            if ($dd > $maxDd) $maxDd = $dd;
        }
        return round($maxDd, 2);
    }

    private function weekdayName(int $index): string
    {
        $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        return $days[$index] ?? 'Inconnu';
    }
}
