<div class="space-y-6 max-w-3xl">
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.security.title') }}</h1>

    {{-- Changement de mot de passe --}}
    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5">
        <p class="text-sm font-medium text-texte-principal mb-4">{{ __('app.client.security.change_password') }}</p>
        <form wire:submit="updatePassword" class="space-y-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.security.current_password') }}</label>
                <input type="password" id="current_password" wire:model="current_password" autocomplete="current-password"
                    class="w-full max-w-md rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                @error('current_password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.security.new_password') }}</label>
                <input type="password" id="password" wire:model="password" autocomplete="new-password"
                    class="w-full max-w-md rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                @error('password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.security.confirm_password') }}</label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation" autocomplete="new-password"
                    class="w-full max-w-md rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            </div>
            <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"
                class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2.5 hover:brightness-110 transition disabled:opacity-60">
                {{ __('app.common.save') }}
            </button>
        </form>
    </div>

    {{-- 2FA --}}
    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-texte-principal">{{ __('app.client.security.two_factor') }}</p>
            <p class="text-xs text-texte-secondaire mt-1">{{ __('app.client.security.two_factor_desc') }}</p>
        </div>
        <x-toggle-switch wire:click="toggleTwoFactor" :checked="$two_factor_enabled" wire:loading.attr="disabled" wire:target="toggleTwoFactor" />
    </div>

    {{-- Sessions actives --}}
    <div>
        <p class="text-sm font-medium text-texte-principal mb-3">{{ __('app.client.security.active_sessions') }}</p>
        <x-data-table :headers="[__('app.client.security.device'), __('app.client.security.ip_address'), __('app.client.security.last_activity'), '']">
            @forelse($sessions as $s)
                <tr>
                    <td class="px-4 py-3 max-w-xs truncate">{{ \Illuminate\Support\Str::limit($s->user_agent, 60) ?: '—' }}</td>
                    <td class="px-4 py-3">{{ $s->ip_address ?? '—' }}</td>
                    <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::createFromTimestamp($s->last_activity)->diffForHumans() }}</td>
                    <td class="px-4 py-3 text-right">
                        @if($s->id === $currentSessionId)
                            <span class="text-xs text-succes">{{ __('app.client.security.session_current') }}</span>
                        @else
                            <button type="button" wire:click="terminateSession('{{ $s->id }}')" wire:confirm="{{ __('app.common.confirm') }}?"
                                class="text-xs text-danger hover:underline">
                                {{ __('app.client.security.session_terminate') }}
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.common.no_results') }}</td></tr>
            @endforelse
        </x-data-table>
    </div>
</div>
