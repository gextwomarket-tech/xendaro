<div>
    <h3 class="font-display text-lg font-semibold text-texte-principal">{{ __('app.client.wallet.topup_demo_title') }}</h3>
    <p class="mt-1 text-xs text-texte-secondaire">{{ __('app.client.wallet.balance_demo') }}: ${{ number_format(auth()->user()->wallet->solde_demo ?? 0, 2) }}</p>

    <form wire:submit="submit" class="mt-5 space-y-4">
        <div>
            <label for="topup-amount" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.wallet.topup_demo_amount') }}</label>
            <input type="number" step="0.01" min="100" id="topup-amount" wire:model="montant"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('montant') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach([1000, 5000, 10000, 50000] as $preset)
                <button type="button" wire:click="$set('montant', {{ $preset }})"
                    class="rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-couleur-principale hover:border-couleur-principale/50 text-xs font-medium px-3 py-1.5 transition">
                    +${{ number_format($preset) }}
                </button>
            @endforeach
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" x-on:click="$dispatch('close-modal', { name: 'topup-demo' })" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal text-sm font-medium px-4 py-2 transition">
                {{ __('app.common.cancel') }}
            </button>
            <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                class="inline-flex items-center rounded-sm bg-couleur-secondaire text-fond-principal text-sm font-semibold px-4 py-2 hover:brightness-110 transition disabled:opacity-60">
                {{ __('app.client.wallet.topup_demo_button') }}
            </button>
        </div>
    </form>
</div>
