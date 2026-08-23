<div>
    <h3 class="font-display text-lg font-semibold text-texte-principal">{{ __('app.client.support.new_ticket') }}</h3>

    <form wire:submit="submit" class="mt-5 space-y-4">
        <div>
            <label for="ticket-subject" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.support.subject') }}</label>
            <input type="text" id="ticket-subject" wire:model="sujet"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('sujet') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="ticket-message" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.support.message') }}</label>
            <textarea id="ticket-message" wire:model="message" rows="5"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale"></textarea>
            @error('message') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" x-on:click="$dispatch('close-modal', { name: 'new-ticket' })" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal text-sm font-medium px-4 py-2 transition">
                {{ __('app.common.cancel') }}
            </button>
            <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2 hover:brightness-110 transition disabled:opacity-60">
                {{ __('app.common.send') }}
            </button>
        </div>
    </form>
</div>
