<div>
    {{--
        Dialog de trade rapide SANS overlay (type MT5), voir xendaro-fox-plan.json > Page id 37 >
        popup_modal. Reutilise resources/views/components/modal.blade.php avec :overlay="false"
        (deja prevu pour cet usage exact) - ne pas utiliser la modale standard avec overlay ici.
    --}}
    <x-modal name="quick-trade" :overlay="false" max-width="sm">
        <h3 class="font-display font-semibold text-texte-principal mb-3">
            {{ __('app.trade.quick_trade_title') }}
            @if($instrument)
                <span class="text-texte-secondaire font-normal">- {{ $instrument->nom }}</span>
            @endif
        </h3>

        @if($instrumentId)
            <livewire:trade.order-form :instrument-id="$instrumentId" :mode-actif="$modeActif" variant="quick" wire:key="order-form-quick" />
        @else
            <p class="text-sm text-texte-secondaire">{{ __('app.trade.select_instrument_first') }}</p>
        @endif
    </x-modal>
</div>
