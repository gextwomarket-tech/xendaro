<div>
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.auth.forgot_password_title') }}</h1>
    <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.auth.forgot_password_text') }}</p>

    @if($sent)
        <p class="mt-6 rounded-sm bg-succes/10 text-succes text-sm px-3 py-3">{{ __('app.auth.reset_link_sent') }}</p>
    @else
        <form wire:submit="sendResetLink" class="mt-6 space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.email') }}</label>
                <input type="email" id="email" wire:model="email" autocomplete="email"
                    class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="sendResetLink"
                class="w-full inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold py-2.5 hover:brightness-110 transition disabled:opacity-60">
                <span wire:loading.remove wire:target="sendResetLink">{{ __('app.auth.send_reset_link') }}</span>
                <span wire:loading wire:target="sendResetLink">{{ __('app.common.loading') }}</span>
            </button>
        </form>
    @endif

    <p class="mt-6 text-center text-sm text-texte-secondaire">
        <a href="{{ url('/connexion') }}" class="text-couleur-principale hover:underline font-medium">{{ __('app.auth.back_to_login') }}</a>
    </p>
</div>
