<div>
    <form wire:submit="submit" class="flex items-end gap-3">
        <div class="flex-1">
            <textarea wire:model="message" rows="2" placeholder="{{ __('app.client.support.message') }}"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale"></textarea>
            @error('message') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>
        <button type="submit" wire:loading.attr="disabled" wire:target="submit"
            class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2.5 hover:brightness-110 transition disabled:opacity-60 shrink-0">
            {{ __('app.client.support.send_message') }}
        </button>
    </form>
</div>
