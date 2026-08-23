<div>
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.auth.reset_password_title') }}</h1>

    <form wire:submit="resetPassword" class="mt-6 space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.email') }}</label>
            <input type="email" id="email" wire:model="email" autocomplete="email"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.new_password') }}</label>
            <input type="password" id="password" wire:model="password" autocomplete="new-password"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.confirm_password') }}</label>
            <input type="password" id="password_confirmation" wire:model="password_confirmation" autocomplete="new-password"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="resetPassword"
            class="w-full inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold py-2.5 hover:brightness-110 transition disabled:opacity-60">
            <span wire:loading.remove wire:target="resetPassword">{{ __('app.auth.reset_password_button') }}</span>
            <span wire:loading wire:target="resetPassword">{{ __('app.common.loading') }}</span>
        </button>
    </form>
</div>
