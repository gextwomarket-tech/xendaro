<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';

    protected static ?string $heading = 'Actions Rapides';

    protected static ?int $sort = 1;

    public function getQuickActions(): array
    {
        return [
            [
                'label' => 'Utilisateurs',
                'icon' => 'users',
                'url' => route('filament.admin.resources.users.index'),
                'color' => 'blue',
            ],
            [
                'label' => 'Portefeuilles',
                'icon' => 'wallet',
                'url' => route('filament.admin.resources.wallets.index'),
                'color' => 'green',
            ],
            [
                'label' => 'Trades',
                'icon' => 'chart-bar',
                'url' => route('filament.admin.resources.trades.index'),
                'color' => 'purple',
            ],
            [
                'label' => 'Commandes',
                'icon' => 'list-bullet',
                'url' => route('filament.admin.resources.orders.index'),
                'color' => 'orange',
            ],
            [
                'label' => 'Transactions',
                'icon' => 'banknotes',
                'url' => route('filament.admin.resources.transactions.index'),
                'color' => 'emerald',
            ],
            [
                'label' => 'Paramètres',
                'icon' => 'cog-6-tooth',
                'url' => route('filament.admin.resources.platform-settings.index'),
                'color' => 'red',
            ],
        ];
    }
}
