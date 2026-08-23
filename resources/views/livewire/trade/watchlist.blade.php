<div class="flex flex-col h-full min-h-0" wire:poll.3s>
    <div class="p-3 space-y-2 shrink-0 border-b border-bordure-subtile">
        <p class="text-xs font-medium uppercase tracking-wide text-texte-secondaire">{{ __('app.trade.watchlist_title') }}</p>

        <x-search-input wire:model.live.debounce.400ms="search" placeholder="{{ __('app.common.search') }}" />

        <x-select-filter
            wire:model.live="categorieFilter"
            :options="[
                'forex' => __('app.trade.categories.forex'),
                'crypto' => __('app.trade.categories.crypto'),
                'metal' => __('app.trade.categories.metal'),
                'commodite' => __('app.trade.categories.commodite'),
                'indice' => __('app.trade.categories.indice'),
                'action' => __('app.trade.categories.action'),
            ]"
            :placeholder="__('app.common.all')"
            class="w-full"
        />
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto divide-y divide-bordure-subtile">
        @forelse($instruments as $instrument)
            @php
                $isActive = $instrument->id === $activeInstrumentId;
                $variation = (float) $instrument->variation_calculee;
            @endphp
            <div
                wire:key="watchlist-row-{{ $instrument->id }}"
                wire:click="selectInstrument({{ $instrument->id }})"
                class="flex items-center justify-between gap-2 px-3 py-2.5 cursor-pointer hover:bg-fond-card transition-colors {{ $isActive ? 'bg-fond-card border-l-2 border-couleur-principale' : 'border-l-2 border-transparent' }}"
            >
                <div class="min-w-0">
                    <p class="text-sm font-medium text-texte-principal truncate">{{ $instrument->symbole_interne }}</p>
                    <p class="text-[11px] text-texte-secondaire truncate">{{ $instrument->nom }}</p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <div class="text-right">
                        <p class="text-sm font-medium tabular-nums text-texte-principal">{{ number_format($instrument->prix_actuel_calcule, $instrument->prix_actuel_calcule < 10 ? 5 : 2) }}</p>
                        <p class="text-[11px] tabular-nums {{ $variation >= 0 ? 'text-succes' : 'text-danger' }}">
                            {{ $variation >= 0 ? '+' : '' }}{{ $variation }}%
                        </p>
                    </div>

                    <button
                        type="button"
                        wire:click.stop="openQuickTrade({{ $instrument->id }})"
                        title="{{ __('app.trade.quick_trade_title') }}"
                        class="shrink-0 w-7 h-7 rounded-sm flex items-center justify-center text-texte-secondaire hover:text-couleur-principale hover:bg-fond-surface transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <p class="p-4 text-sm text-texte-secondaire text-center">{{ __('app.common.no_results') }}</p>
        @endforelse
    </div>
</div>
