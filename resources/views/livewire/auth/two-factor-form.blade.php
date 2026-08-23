<div x-data="{
        remaining: Math.max(0, {{ $resendAvailableAt ?? 0 }} - Math.floor(Date.now() / 1000)),
        tick() {
            if (this.remaining > 0) { this.remaining--; setTimeout(() => this.tick(), 1000); }
        }
    }"
    x-init="tick()"
>
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.auth.two_factor_title') }}</h1>
    <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.auth.two_factor_text', ['email' => auth()->user()->email]) }}</p>

    <form wire:submit="verify" class="mt-6 space-y-4">
        <div>
            <label for="code" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.auth.otp_code') }}</label>
            <input type="text" inputmode="numeric" maxlength="6" id="code" wire:model="code" autocomplete="one-time-code"
                class="w-full text-center tracking-[0.5em] text-xl font-display rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-3 text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('code') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="verify"
            class="w-full inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold py-2.5 hover:brightness-110 transition disabled:opacity-60">
            <span wire:loading.remove wire:target="verify">{{ __('app.auth.verify_button') }}</span>
            <span wire:loading wire:target="verify">{{ __('app.common.loading') }}</span>
        </button>
    </form>

    <div class="mt-6 text-center text-sm">
        <button type="button" wire:click="resend" x-bind:disabled="remaining > 0"
            class="text-couleur-principale hover:underline font-medium disabled:opacity-50 disabled:cursor-not-allowed disabled:no-underline" x-bind:class="remaining > 0 && 'text-texte-secondaire'">
            <span x-show="remaining === 0">{{ __('app.auth.resend_code') }}</span>
            <span x-show="remaining > 0" x-text="'{{ __('app.auth.resend_code_in', ['seconds' => '__SECONDS__']) }}'.replace('__SECONDS__', remaining)"></span>
        </button>
    </div>
</div>
