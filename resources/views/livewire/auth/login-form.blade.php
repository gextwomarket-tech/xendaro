<div>
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.auth.login_title') }}</h1>
    <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.auth.welcome_title') }}</p>

    <form wire:submit="login" class="mt-6 space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.email') }}</label>
            <input type="email" id="email" wire:model="email" autocomplete="email"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-texte-secondaire">{{ __('app.auth.password') }}</label>
                <a href="{{ url('/mot-de-passe-oublie') }}" class="text-xs text-couleur-principale hover:underline">{{ __('app.auth.forgot_password') }}</a>
            </div>
            <input type="password" id="password" wire:model="password" autocomplete="current-password"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-texte-secondaire cursor-pointer">
            <input type="checkbox" wire:model="remember" class="rounded border-bordure-subtile bg-fond-surface text-couleur-principale focus:ring-couleur-principale">
            {{ __('app.auth.remember_me') }}
        </label>

        <button type="submit" wire:loading.attr="disabled" wire:target="login"
            class="w-full inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold py-2.5 hover:brightness-110 transition disabled:opacity-60">
            <span wire:loading.remove wire:target="login">{{ __('app.auth.login_button') }}</span>
            <span wire:loading wire:target="login">{{ __('app.common.loading') }}</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-texte-secondaire">
        {{ __('app.auth.no_account') }}
        <a href="{{ url('/inscription') }}" class="text-couleur-principale hover:underline font-medium">{{ __('app.nav.register') }}</a>
    </p>
</div>
