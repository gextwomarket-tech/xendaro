<?php

namespace App\Filament\Widgets;

use App\Models\KycDocument;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewAdminWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $newUsersToday   = User::whereDate('created_at', today())->count();
        $pendingKyc      = KycDocument::where('status', 'pending')->count();
        $pendingDeposits = Transaction::whereIn('type', ['deposit'])
            ->where('status', 'pending')->count();
        $pendingWithdrawals = Transaction::whereIn('type', ['withdraw', 'withdrawal'])
            ->where('status', 'pending')->count();

        return [
            Stat::make('Total Utilisateurs', User::count())
                ->description($newUsersToday . ' inscrits aujourd\'hui')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-users'),

            Stat::make('KYC en attente', $pendingKyc)
                ->description('Documents à vérifier')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingKyc > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-shield-check'),

            Stat::make('Trades ouverts', Trade::open()->count())
                ->description('Positions actives')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->icon('heroicon-o-chart-bar'),

            Stat::make('Dépôts en attente', $pendingDeposits)
                ->description('À approuver')
                ->descriptionIcon('heroicon-m-arrow-down-circle')
                ->color($pendingDeposits > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-arrow-down-tray'),

            Stat::make('Retraits en attente', $pendingWithdrawals)
                ->description('À approuver')
                ->descriptionIcon('heroicon-m-arrow-up-circle')
                ->color($pendingWithdrawals > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-arrow-up-tray'),

            Stat::make('Balance totale', '$' . number_format(Wallet::sum('balance'), 2))
                ->description('Liquidité plateforme')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
