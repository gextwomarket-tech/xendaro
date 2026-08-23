@props(['user'])
@php
    $initial = \Illuminate\Support\Str::of($user->name ?? '?')->substr(0, 1)->upper();
@endphp
{{--
    Dropdown profil declenche depuis l'avatar de la navbar dashboard (id 31),
    voir Instructions_de_base.design_ui_ux.reference_dashboard_samify.
    Items: Parametres (-> security-settings), Mon profil (-> ouvre modale edit-profile),
    Deconnexion (-> ouvre modale logout-confirm).
--}}
<div x-data="{ open: false }" class="relative" x-on:keydown.escape.window="open = false">
    <button type="button" x-on:click="open = !open" class="flex items-center gap-2 rounded-full focus:outline-none focus:ring-1 focus:ring-couleur-principale">
        <div class="w-9 h-9 rounded-full bg-couleur-principale/15 flex items-center justify-center text-sm font-semibold text-couleur-principale overflow-hidden">
            @if(!empty($user->avatar_path))
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
            @else
                {{ $initial }}
            @endif
        </div>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-on:click.outside="open = false"
        class="absolute right-0 mt-2 w-64 rounded-sm bg-fond-card border border-bordure-subtile shadow-2xl py-2 z-50"
    >
        <div class="px-4 py-2 border-b border-bordure-subtile">
            <p class="text-sm font-medium text-texte-principal truncate">{{ $user->name }}</p>
            <p class="text-xs text-texte-secondaire truncate">{{ $user->email }}</p>
        </div>

        <p class="px-4 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-wide text-texte-secondaire">{{ __('app.client.account_menu') }}</p>

        <a href="{{ route('client.security-settings') }}" class="flex items-center justify-between gap-2 px-4 py-2 text-sm text-texte-principal hover:bg-fond-surface transition">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-texte-secondaire" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ __('app.client.settings') }}
            </span>
            <svg class="w-4 h-4 text-texte-secondaire" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <button type="button" x-on:click="open = false; $dispatch('open-modal', { name: 'edit-profile' })" class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm text-texte-principal hover:bg-fond-surface transition">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-texte-secondaire" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                {{ __('app.client.my_profile') }}
            </span>
            <svg class="w-4 h-4 text-texte-secondaire" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="my-1 border-t border-bordure-subtile"></div>

        <button type="button" x-on:click="open = false; $dispatch('open-modal', { name: 'logout-confirm' })" class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm text-danger hover:bg-danger/10 transition">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                {{ __('app.dashboard.logout') }}
            </span>
        </button>
    </div>
</div>
