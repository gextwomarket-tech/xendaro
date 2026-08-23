@php
    // Sparkline SVG simple (sans dependance JS) a partir du P&L cumule des derniers trades clotures.
    $closedTrades = auth()->user()->tradeHistories()->where('statut', 'cloture')->orderBy('cloture_le')->limit(30)->get();
    $points = [];
    $running = (float) ($wallet->solde_reel ?? 0);
    foreach ($closedTrades as $t) {
        $running += (float) $t->profit_perte;
        $points[] = $running;
    }
    if (count($points) < 2) {
        $base = (float) ($wallet->solde_reel ?? 0);
        $points = [$base, $base];
    }
    $min = min($points);
    $max = max($points);
    $range = max($max - $min, 1);
    $svgWidth = 600;
    $svgHeight = 120;
    $step = $svgWidth / max(count($points) - 1, 1);
    $coords = collect($points)->values()->map(function ($v, $i) use ($step, $svgHeight, $min, $range) {
        $x = round($i * $step, 2);
        $y = round($svgHeight - (($v - $min) / $range) * ($svgHeight - 10) - 5, 2);
        return "$x,$y";
    })->implode(' ');
    $trendUp = end($points) >= $points[0];
@endphp

<div class="space-y-6">
    <div>
        <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.dashboard.welcome', ['name' => auth()->user()->name]) }}</h1>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card :label="__('app.client.dashboard.balance_real')" :value="'$'.number_format($wallet->solde_reel ?? 0, 2)" />
        <x-stat-card :label="__('app.client.dashboard.balance_demo')" :value="'$'.number_format($wallet->solde_demo ?? 0, 2)" />
        <x-stat-card :label="__('app.client.dashboard.total_trades')" :value="$totalTrades" />
        <x-stat-card :label="__('app.client.dashboard.total_pnl')" :value="'$'.number_format($totalPnl, 2)" :trend="$totalPnl == 0 ? null : ($totalPnl > 0 ? 1 : -1)" />
    </div>

    {{-- Graphique de performance --}}
    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5">
        <p class="text-sm font-medium text-texte-principal mb-4">{{ __('app.client.dashboard.performance_chart') }}</p>
        <svg viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}" class="w-full h-32" preserveAspectRatio="none">
            <polyline
                fill="none"
                stroke="{{ $trendUp ? 'var(--color-succes)' : 'var(--color-danger)' }}"
                stroke-width="2"
                points="{{ $coords }}"
            />
        </svg>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Trades recents --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-texte-principal">{{ __('app.client.dashboard.recent_trades') }}</p>
                <a href="{{ route('client.trade-history') }}" class="text-xs text-couleur-principale hover:underline">{{ __('app.common.see_all') }}</a>
            </div>
            <x-data-table :headers="[__('app.client.trade_history.instrument'), __('app.client.trade_history.sens'), __('app.client.trade_history.pnl')]">
                @forelse($recentTrades as $trade)
                    <tr>
                        <td class="px-4 py-3">{{ $trade->instrument->nom ?? '—' }}</td>
                        <td class="px-4 py-3 uppercase text-xs">
                            <span class="{{ $trade->sens === 'buy' ? 'text-succes' : 'text-danger' }}">{{ $trade->sens }}</span>
                        </td>
                        <td class="px-4 py-3 tabular-nums {{ ($trade->profit_perte ?? 0) >= 0 ? 'text-succes' : 'text-danger' }}">
                            {{ $trade->profit_perte !== null ? '$'.number_format($trade->profit_perte, 2) : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.client.dashboard.no_trades') }}</td></tr>
                @endforelse
            </x-data-table>
        </div>

        {{-- Transactions recentes --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-texte-principal">{{ __('app.client.dashboard.recent_transactions') }}</p>
                <a href="{{ route('client.wallet') }}" class="text-xs text-couleur-principale hover:underline">{{ __('app.common.see_all') }}</a>
            </div>
            <x-data-table :headers="[__('app.client.wallet.type'), __('app.client.wallet.amount'), __('app.client.wallet.status')]">
                @forelse($recentTransactions as $tx)
                    <tr>
                        <td class="px-4 py-3">{{ $tx->type === 'depot' ? __('app.client.wallet.type_deposit') : __('app.client.wallet.type_withdraw') }}</td>
                        <td class="px-4 py-3 tabular-nums">${{ number_format($tx->montant, 2) }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$tx->statut" /></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.client.dashboard.no_transactions') }}</td></tr>
                @endforelse
            </x-data-table>
        </div>
    </div>
</div>
