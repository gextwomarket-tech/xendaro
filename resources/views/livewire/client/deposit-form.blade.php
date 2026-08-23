<div>
    <h3 class="font-display text-lg font-semibold text-texte-principal">{{ __('app.client.wallet.deposit_title') }}</h3>

    <form wire:submit="submit" class="mt-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.wallet.payment_method') }}</label>
            <x-select-filter wire:model.live="payment_method_id" :options="$paymentMethods->pluck('nom', 'id')->toArray()" :placeholder="__('app.client.wallet.payment_method')" class="w-full" />
            @error('payment_method_id') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        @if($selectedMethod)
            {{-- Instructions repliables: peuvent etre longues (IBAN + conditions, reseau crypto...) --}}
            <x-accordion>
                <x-accordion-item :title="__('app.client.wallet.deposit_details_label')" :open="true">
                        <div class="space-y-4">
                            <p class="leading-relaxed">{{ $selectedMethod->instructions }}</p>

                            @if($selectedMethod->details_paiement)
                                <div class="flex flex-col sm:flex-row gap-4 items-start">
                                    <div class="flex-1 w-full space-y-1.5">
                                        <label class="block text-xs font-medium text-texte-secondaire">
                                            @if($selectedMethod->type === 'crypto')
                                                {{ __('app.client.wallet.crypto_address_label') }}
                                            @else
                                                {{ __('app.client.wallet.deposit_details_label') }}
                                            @endif
                                        </label>
                                        <div x-data="{ copied: false }" class="flex items-center gap-2">
                                            <input
                                                type="text"
                                                readonly
                                                value="{{ $selectedMethod->details_paiement }}"
                                                onclick="this.select()"
                                                class="flex-1 min-w-0 rounded-sm bg-fond-card border border-bordure-subtile px-3 py-2.5 text-xs text-couleur-principale font-mono cursor-text focus:outline-none focus:ring-1 focus:ring-couleur-principale"
                                            >
                                            <button
                                                type="button"
                                                x-on:click="navigator.clipboard.writeText(@js($selectedMethod->details_paiement)); copied = true; setTimeout(() => copied = false, 1500)"
                                                class="shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-couleur-principale hover:border-couleur-principale/50 transition"
                                                :aria-label="'{{ __('app.common.copy') }}'"
                                            >
                                                <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                                <svg x-show="copied" x-cloak class="w-4 h-4 text-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- QR code (crypto uniquement): genere via une API d'image externe,
                                         necessite une connexion internet - simple aide visuelle, pas critique. --}}
                                    @if($selectedMethod->type === 'crypto')
                                        <div class="shrink-0 mx-auto sm:mx-0 rounded-sm bg-white p-2">
                                            <img
                                                src="https://api.qrserver.com/v1/create-qr-code/?size=112x112&data={{ urlencode($selectedMethod->details_paiement) }}"
                                                alt="{{ __('app.client.wallet.qr_code_alt') }}"
                                                width="112" height="112"
                                                loading="lazy"
                                            >
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <p class="text-xs">
                                <span class="font-medium text-texte-principal">{{ __('app.client.wallet.delay_label') }}:</span>
                                {{ $selectedMethod->delai_traitement }}
                            </p>
                        </div>
                    </x-accordion-item>
            </x-accordion>

            <p class="text-xs text-avertissement bg-avertissement/10 rounded-sm px-3 py-2">
                {{ __('app.client.wallet.deposit_manual_notice') }}
            </p>
        @endif

        <div>
            <label for="deposit-amount" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.wallet.amount') }}</label>
            <input type="number" step="0.01" min="10" id="deposit-amount" wire:model="montant"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('montant') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" x-on:click="$dispatch('close-modal', { name: 'deposit' })" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal text-sm font-medium px-4 py-2 transition">
                {{ __('app.common.cancel') }}
            </button>
            <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2 hover:brightness-110 transition disabled:opacity-60">
                {{ __('app.client.wallet.deposit') }}
            </button>
        </div>
    </form>
</div>
