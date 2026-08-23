<div class="p-3 space-y-3">
    <div class="flex items-center justify-between">
        <p class="text-xs font-medium uppercase tracking-wide text-texte-secondaire">
            {{ $this->instrument->nom ?? __('app.trade.ticker.no_instrument') }}
        </p>
        <div class="flex items-center gap-2">
            <span class="text-xs text-texte-secondaire">{{ $modeReel ? __('app.trade.mode_real') : __('app.trade.mode_demo') }}</span>
            <x-toggle-switch wire:model.live="modeReel" :checked="$modeReel" />
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs text-texte-secondaire mb-1" for="volume-{{ $this->getId() }}">{{ __('app.trade.volume_label') }}</label>
            <input
                id="volume-{{ $this->getId() }}"
                type="number"
                step="0.01"
                min="0.01"
                wire:model.live.debounce.300ms="volume"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-sm text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale"
            >
            @error('volume') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs text-texte-secondaire mb-1" for="type-ordre-{{ $this->getId() }}">{{ __('app.trade.order_type_label') }}</label>
            <x-select-filter
                id="type-ordre-{{ $this->getId() }}"
                wire:model.live="typeOrdre"
                :options="[
                    'marche' => __('app.trade.order_types.marche'),
                    'buy_limit' => __('app.trade.order_types.buy_limit'),
                    'sell_limit' => __('app.trade.order_types.sell_limit'),
                    'buy_stop' => __('app.trade.order_types.buy_stop'),
                    'sell_stop' => __('app.trade.order_types.sell_stop'),
                ]"
                class="w-full"
            />
        </div>

        <div>
            <label class="block text-xs text-texte-secondaire mb-1" for="sl-{{ $this->getId() }}">{{ __('app.trade.stop_loss_label') }}</label>
            <input
                id="sl-{{ $this->getId() }}"
                type="number"
                step="0.00001"
                min="0"
                wire:model.blur="stopLoss"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-sm text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale"
            >
            @error('stopLoss') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs text-texte-secondaire mb-1" for="tp-{{ $this->getId() }}">{{ __('app.trade.take_profit_label') }}</label>
            <input
                id="tp-{{ $this->getId() }}"
                type="number"
                step="0.00001"
                min="0"
                wire:model.blur="takeProfit"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-sm text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale"
            >
            @error('takeProfit') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center justify-between text-xs text-texte-secondaire">
        <span>{{ __('app.trade.current_price') }}: <span class="tabular-nums text-texte-principal">{{ number_format($this->prixActuel, 5) }}</span></span>
        <span>{{ __('app.trade.margin_required') }}: <span class="tabular-nums text-texte-principal">{{ number_format($this->margeRequise, 2) }} $</span></span>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <button
            type="button"
            wire:click="placerOrdre('sell')"
            wire:loading.attr="disabled"
            @disabled(! $this->instrument)
            class="rounded-sm bg-danger text-white font-semibold py-2.5 text-sm hover:brightness-110 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
            {{ __('app.common.sell') }}
        </button>
        <button
            type="button"
            wire:click="placerOrdre('buy')"
            wire:loading.attr="disabled"
            @disabled(! $this->instrument)
            class="rounded-sm bg-succes/90 text-white font-semibold py-2.5 text-sm hover:brightness-110 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
            {{ __('app.common.buy') }}
        </button>
    </div>
</div>
