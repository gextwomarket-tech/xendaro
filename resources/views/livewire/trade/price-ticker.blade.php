<div class="flex items-center gap-4 px-3 py-2 border-b border-bordure-subtile shrink-0 bg-fond-surface/60 text-sm" wire:poll.2s>
    @if($instrument && $quote)
        <span class="font-medium text-texte-principal">{{ $instrument->symbole_interne }}</span>

        <span class="flex items-center gap-1.5">
            <span class="text-texte-secondaire text-xs">{{ __('app.trade.ticker.bid') }}</span>
            <span class="tabular-nums font-semibold transition-colors duration-500 {{ $direction === 'up' ? 'text-succes' : ($direction === 'down' ? 'text-danger' : 'text-texte-principal') }}">
                {{ number_format($quote['bid'], 5) }}
            </span>
        </span>

        <span class="flex items-center gap-1.5">
            <span class="text-texte-secondaire text-xs">{{ __('app.trade.ticker.ask') }}</span>
            <span class="tabular-nums font-semibold transition-colors duration-500 {{ $direction === 'up' ? 'text-succes' : ($direction === 'down' ? 'text-danger' : 'text-texte-principal') }}">
                {{ number_format($quote['ask'], 5) }}
            </span>
        </span>

        <span class="flex items-center gap-1.5 text-texte-secondaire text-xs ml-auto">
            {{ __('app.trade.ticker.spread') }}: <span class="tabular-nums text-texte-principal">{{ number_format($quote['spread'], 5) }}</span>
        </span>
    @else
        <span class="text-texte-secondaire text-xs">{{ __('app.trade.ticker.no_instrument') }}</span>
    @endif
</div>
