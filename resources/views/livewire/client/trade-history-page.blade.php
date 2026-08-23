<div class="space-y-6">
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.trade_history.title') }}</h1>

    {{-- Filtres --}}
    <div class="flex flex-wrap items-center gap-3">
        <x-select-filter wire:model.live="mode" :options="['demo' => __('app.client.trade_history.mode_demo'), 'reel' => __('app.client.trade_history.mode_reel')]" :placeholder="__('app.client.trade_history.all_modes')" />
        <x-select-filter wire:model.live="instrumentId" :options="$instruments->pluck('nom', 'id')->toArray()" :placeholder="__('app.common.all')" />
        <input type="date" wire:model.live="dateFrom" class="rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
        <input type="date" wire:model.live="dateTo" class="rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
    </div>

    <x-data-table :headers="[
        __('app.client.trade_history.instrument'),
        __('app.client.trade_history.sens'),
        __('app.client.trade_history.volume'),
        __('app.client.trade_history.open_price'),
        __('app.client.trade_history.close_price'),
        __('app.client.trade_history.pnl'),
        __('app.client.trade_history.closed_at'),
    ]">
        @forelse($trades as $trade)
            <tr>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <span>{{ $trade->instrument->nom ?? '—' }}</span>
                        <span class="text-[10px] uppercase text-texte-secondaire">{{ $trade->mode }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 uppercase text-xs">
                    <span class="{{ $trade->sens === 'buy' ? 'text-succes' : 'text-danger' }}">{{ $trade->sens }}</span>
                </td>
                <td class="px-4 py-3 tabular-nums">{{ number_format($trade->volume, 2) }}</td>
                <td class="px-4 py-3 tabular-nums">{{ number_format($trade->prix_ouverture, 5) }}</td>
                <td class="px-4 py-3 tabular-nums">{{ $trade->prix_cloture !== null ? number_format($trade->prix_cloture, 5) : '—' }}</td>
                <td class="px-4 py-3 tabular-nums {{ ($trade->profit_perte ?? 0) >= 0 ? 'text-succes' : 'text-danger' }}">
                    {{ $trade->profit_perte !== null ? '$'.number_format($trade->profit_perte, 2) : '—' }}
                </td>
                <td class="px-4 py-3 text-texte-secondaire text-xs">{{ $trade->cloture_le?->format('d/m/Y H:i') ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.client.trade_history.no_results') }}</td></tr>
        @endforelse

        <x-slot:pagination>{{ $trades->links() }}</x-slot:pagination>
    </x-data-table>
</div>
