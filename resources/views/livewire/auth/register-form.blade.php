<div>
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.auth.register_title') }}</h1>
    <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.auth.welcome_subtitle') }}</p>

    @if($ref)
        <p class="mt-3 text-xs rounded-sm bg-succes/10 text-succes px-3 py-2">{{ __('app.auth.referral_applied') }}</p>
    @endif

    <form wire:submit="register" class="mt-6 space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.name') }}</label>
            <input type="text" id="name" wire:model="name" autocomplete="name"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.email') }}</label>
            <input type="email" id="email" wire:model="email" autocomplete="email"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.phone') }} <span class="text-texte-secondaire/70">({{ __('app.common.optional') }})</span></label>
            <input type="text" id="phone" wire:model="phone" autocomplete="tel"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('phone') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.password') }}</label>
            <input type="password" id="password" wire:model="password" autocomplete="new-password"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.confirm_password') }}</label>
            <input type="password" id="password_confirmation" wire:model="password_confirmation" autocomplete="new-password"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
        </div>

        <div>
            <label class="flex items-start gap-2 text-sm text-texte-secondaire cursor-pointer">
                <input type="checkbox" wire:model="accept_terms" class="mt-0.5 rounded border-bordure-subtile bg-fond-surface text-couleur-principale focus:ring-couleur-principale">
                <span>
                    {!! str_replace(
                        [':cgv', ':policies'],
                        ['<a href="'.url('/cgv').'" class="text-couleur-principale hover:underline" target="_blank">'.__('app.auth.terms_link').'</a>', '<a href="'.url('/confidentialite').'" class="text-couleur-principale hover:underline" target="_blank">'.__('app.auth.policies_link').'</a>'],
                        __('app.auth.accept_terms')
                    ) !!}
                </span>
            </label>
            @error('accept_terms') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="register"
            class="w-full inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold py-2.5 hover:brightness-110 transition disabled:opacity-60">
            <span wire:loading.remove wire:target="register">{{ __('app.auth.register_button') }}</span>
            <span wire:loading wire:target="register">{{ __('app.common.loading') }}</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-texte-secondaire">
        {{ __('app.auth.have_account') }}
        <a href="{{ url('/connexion') }}" class="text-couleur-principale hover:underline font-medium">{{ __('app.nav.login') }}</a>
    </p>
</div>
