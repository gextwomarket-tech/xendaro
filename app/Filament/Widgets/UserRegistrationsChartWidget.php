<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class UserRegistrationsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Inscriptions utilisateurs (30 derniers jours)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $days  = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i)->format('Y-m-d'));
        $raw   = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $data   = $days->map(fn ($d) => (int) ($raw[$d] ?? 0));
        $labels = $days->map(fn ($d) => Carbon::parse($d)->format('d/m'));

        return [
            'datasets' => [
                [
                    'label'           => 'Nouveaux utilisateurs',
                    'data'            => $data->values()->toArray(),
                    'borderColor'     => '#547A95',
                    'backgroundColor' => 'rgba(84,122,149,0.15)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'pointRadius'     => 3,
                ],
            ],
            'labels' => $labels->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales'  => [
                'y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]],
            ],
        ];
    }
}
