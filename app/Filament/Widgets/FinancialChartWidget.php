<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class FinancialChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Dépôts vs Retraits (30 derniers jours)';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i)->format('Y-m-d'));
        $from = Carbon::today()->subDays(29)->startOfDay();

        $deposits = Transaction::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('type', 'deposit')
            ->where('created_at', '>=', $from)
            ->groupBy('date')
            ->pluck('total', 'date');

        $withdrawals = Transaction::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereIn('type', ['withdraw', 'withdrawal'])
            ->where('created_at', '>=', $from)
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels      = $days->map(fn ($d) => Carbon::parse($d)->format('d/m'));
        $depData     = $days->map(fn ($d) => (float) ($deposits[$d] ?? 0));
        $withData    = $days->map(fn ($d) => (float) ($withdrawals[$d] ?? 0));

        return [
            'datasets' => [
                [
                    'label'           => 'Dépôts ($)',
                    'data'            => $depData->values()->toArray(),
                    'backgroundColor' => 'rgba(16,185,129,0.7)',
                    'borderColor'     => '#10b981',
                    'borderWidth'     => 1,
                ],
                [
                    'label'           => 'Retraits ($)',
                    'data'            => $withData->values()->toArray(),
                    'backgroundColor' => 'rgba(239,68,68,0.7)',
                    'borderColor'     => '#ef4444',
                    'borderWidth'     => 1,
                ],
            ],
            'labels' => $labels->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => true, 'position' => 'top']],
            'scales'  => ['y' => ['beginAtZero' => true]],
        ];
    }
}
