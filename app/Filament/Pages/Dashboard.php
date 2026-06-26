<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FinancialChartWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentTransactionsWidget;
use App\Filament\Widgets\StatsOverviewAdminWidget;
use App\Filament\Widgets\UserRegistrationsChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $routePath = 'dashboard';

    public function getHeading(): string
    {
        return 'Dashboard Admin — Purprime Fox';
    }

    public function getSubheading(): ?string
    {
        return 'Bienvenue dans votre panneau d\'administration';
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewAdminWidget::class,
            QuickActionsWidget::class,
            UserRegistrationsChartWidget::class,
            FinancialChartWidget::class,
            RecentTransactionsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}
