<div>
    <h3 class="font-display text-lg font-semibold text-texte-principal">{{ __('app.client.wallet.withdraw_title') }}</h3>
    <p class="mt-1 text-xs text-texte-secondaire">{{ __('app.client.wallet.balance_real') }}: ${{ number_format($availableBalance, 2) }}</p>

    <form wire:submit="submit" class="mt-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.wallet.payment_method') }}</label>
            <x-select-filter wire:model="payment_method_id" :options="$paymentMethods->pluck('nom', 'id')->toArray()" :placeholder="__('app.client.wallet.payment_method')" class="w-full" />
            @error('payment_method_id') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="withdraw-amount" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.wallet.amount') }}</label>
            <input type="number" step="0.01" min="10" max="{{ $availableBalance }}" id="withdraw-amount" wire:model="montant"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('montant') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" x-on:click="$dispatch('close-modal', { name: 'withdraw' })" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal text-sm font-medium px-4 py-2 transition">
                {{ __('app.common.cancel') }}
            </button>
            <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                class="inline-flex items-center rounded-sm bg-fond-surface border border-bordure-subtile text-texte-principal text-sm font-semibold px-4 py-2 hover:border-couleur-principale/50 transition disabled:opacity-60">
                {{ __('app.client.wallet.withdraw') }}
            </button>
        </div>
    </form>
</div>
