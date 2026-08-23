<div class="flex flex-col h-full min-h-0" wire:poll.3s>
    <p class="px-3 py-2 text-xs font-medium uppercase tracking-wide text-texte-secondaire shrink-0 border-b border-bordure-subtile">
        {{ __('app.trade.open_positions_title') }}
    </p>

    <div class="flex-1 min-h-0 overflow-y-auto divide-y divide-bordure-subtile">
        @forelse($positions as $trade)
            @php $pnl = (float) $trade->pnl_flottant; @endphp
            <div wire:key="position-row-{{ $trade->id }}" class="px-3 py-2.5 text-xs space-y-1">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-texte-principal">{{ $trade->instrument->symbole_interne ?? '—' }}</span>
                    <span class="{{ $trade->sens === 'buy' ? 'text-succes' : 'text-danger' }} font-medium uppercase">
                        {{ $trade->sens === 'buy' ? __('app.common.buy') : __('app.common.sell') }} {{ number_format((float) $trade->volume, 2) }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-texte-secondaire">
                    <span>{{ number_format((float) $trade->prix_ouverture, 5) }} → <span class="tabular-nums">{{ number_format((float) $trade->prix_actuel_calcule, 5) }}</span></span>
                    <span class="tabular-nums font-semibold {{ $pnl >= 0 ? 'text-succes' : 'text-danger' }}">
                        {{ $pnl >= 0 ? '+' : '' }}{{ number_format($pnl, 2) }} $
                    </span>
                </div>
                <button
                    type="button"
                    wire:click="closePosition({{ $trade->id }})"
                    wire:confirm="{{ __('app.trade.close_position') }} {{ $trade->instrument->symbole_interne ?? '' }} ?"
                    class="w-full mt-1 text-[11px] font-medium rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal hover:border-danger/50 py-1.5 transition"
                >
                    {{ __('app.trade.close_position') }}
                </button>
            </div>
        @empty
            <p class="p-4 text-sm text-texte-secondaire text-center">{{ __('app.trade.no_open_positions') }}</p>
        @endforelse
    </div>
</div>
