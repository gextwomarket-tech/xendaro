<div class="p-3 border-b border-bordure-subtile shrink-0">
    <x-user-mini-card :user="auth()->user()" size="sm">
        <div class="mt-3 space-y-1.5 text-sm">
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-texte-secondaire">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5-1.343 1.5-3 1.5m0-6V6m0 1.5V15m0 1.5V15m0 0c-1.657 0-3-.672-3-1.5M12 3a9 9 0 100 18 9 9 0 000-18z" />
                    </svg>
                    {{ __('app.trade.balance_real') }}
                </span>
                <span class="font-medium tabular-nums text-texte-principal">{{ number_format($wallet->solde_reel ?? 0, 2) }} $</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-texte-secondaire">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 20.25a48.25 48.25 0 01-8.135-.687c-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                    </svg>
                    {{ __('app.trade.balance_demo') }}
                </span>
                <span class="font-medium tabular-nums text-texte-principal">{{ number_format($wallet->solde_demo ?? 0, 2) }} $</span>
            </div>
        </div>

        <div class="mt-3 flex items-center justify-between">
            <span class="text-xs text-texte-secondaire">{{ $modeReel ? __('app.trade.mode_real') : __('app.trade.mode_demo') }}</span>
            <x-toggle-switch wire:model.live="modeReel" :checked="$modeReel" />
        </div>
    </x-user-mini-card>
</div>
