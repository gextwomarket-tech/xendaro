<div>
    <form wire:submit="send" class="space-y-4">
        <div>
            <label for="contact-name" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.contact.name') }}</label>
            <input type="text" id="contact-name" wire:model="nom"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('nom') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="contact-email" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.contact.email') }}</label>
            <input type="email" id="contact-email" wire:model="email"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="contact-subject" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.contact.subject') }}</label>
            <input type="text" id="contact-subject" wire:model="sujet"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('sujet') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="contact-message" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.contact.message') }}</label>
            <textarea id="contact-message" wire:model="message" rows="5"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale"></textarea>
            @error('message') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="send"
            class="inline-flex items-center justify-center w-full rounded-sm bg-couleur-principale text-fond-principal font-semibold px-5 py-3 hover:brightness-110 transition disabled:opacity-60">
            <span wire:loading.remove wire:target="send">{{ __('app.contact.submit') }}</span>
            <span wire:loading wire:target="send">{{ __('app.common.loading') }}</span>
        </button>
    </form>
</div>
