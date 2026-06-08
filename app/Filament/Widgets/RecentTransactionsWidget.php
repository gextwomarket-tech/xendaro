<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Widget;

class RecentTransactionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-transactions-widget';

    protected static ?string $heading = 'Transactions récentes';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function getTransactions()
    {
        return Transaction::with('user')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();
    }
}
