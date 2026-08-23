@props(['title' => null])
@php
    $user = auth()->user();
    $unreadNotifications = $user?->unreadNotifications()->count() ?? 0;
    $openTickets = $user?->tickets()->whereIn('statut', ['ouvert', 'en_cours'])->count() ?? 0;

    $navItem = function (string $routeName, ?string $url = null) {
        if ($url) {
            return request()->is(ltrim($url, '/')) || request()->is(ltrim($url, '/').'/*');
        }

        return \Illuminate\Support\Facades\Route::currentRouteName() === $routeName;
    };
@endphp
{{--
    Layout Espace Client (Pages id 31 a 43 sauf Trade id 37 qui a son propre layout plein ecran).
    Sidebar 2 etats (etendue/reduite) + navbar, patterns Samify (voir xendaro-fox-plan.json
    Instructions_de_base.design_ui_ux.reference_dashboard_samify).
--}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}{{ $title ? ' - '.$title : '' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-fond-principal text-texte-principal font-sans antialiased">

<div
    x-data="{
        sidebarExpanded: localStorage.getItem('xf_sidebar_expanded') !== 'false',
        mobileOpen: false,
        toggle() { this.sidebarExpanded = !this.sidebarExpanded; localStorage.setItem('xf_sidebar_expanded', this.sidebarExpanded); }
    }"
    class="min-h-screen flex"
>
    {{-- Overlay mobile --}}
    <div x-show="mobileOpen" x-cloak x-transition.opacity x-on:click="mobileOpen = false" class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside
        class="fixed lg:sticky top-0 h-screen z-50 bg-fond-surface border-r border-bordure-subtile flex flex-col shrink-0 transition-all duration-200 overflow-hidden"
        :class="[sidebarExpanded ? 'w-64' : 'w-[72px]', mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
    >
        <div class="h-16 flex items-center justify-between px-4 border-b border-bordure-subtile shrink-0">
            <a href="{{ route('client.dashboard') }}" class="flex items-center overflow-hidden" x-show="sidebarExpanded">
                <x-logo size="sm" />
            </a>
            <button type="button" x-on:click="toggle()" class="hidden lg:flex items-center justify-center w-8 h-8 rounded-sm text-texte-secondaire hover:text-texte-principal hover:bg-fond-card transition shrink-0" :class="!sidebarExpanded && 'mx-auto'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarExpanded"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/></svg>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarExpanded" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M6 5l7 7-7 7"/></svg>
            </button>
            <button type="button" x-on:click="mobileOpen = false" class="lg:hidden text-texte-secondaire hover:text-texte-principal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6">
            {{-- Groupe TRADING --}}
            <div>
                <p class="px-2 mb-2 text-[10px] font-semibold uppercase tracking-wider text-texte-secondaire" x-show="sidebarExpanded">{{ __('app.client.nav_group_trading') }}</p>
                <div class="space-y-1">
                    <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('client.dashboard') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.home') }}</span>
                    </a>
                    <a href="{{ route('client.markets') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('client.markets') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.markets') }}</span>
                    </a>
                    <a href="{{ url('/trade') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('trade') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.trade') }}</span>
                    </a>
                    <a href="{{ route('client.trade-history') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('client.trade-history') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.trade_history') }}</span>
                    </a>
                </div>
            </div>

            {{-- Groupe COMPTE --}}
            <div>
                <p class="px-2 mb-2 text-[10px] font-semibold uppercase tracking-wider text-texte-secondaire" x-show="sidebarExpanded">{{ __('app.client.nav_group_account') }}</p>
                <div class="space-y-1">
                    <a href="{{ route('client.wallet') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('client.wallet') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 4h16a1 1 0 001-1V6a1 1 0 00-1-1H4a1 1 0 00-1 1v12a1 1 0 001 1z"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.wallet') }}</span>
                    </a>
                    <a href="{{ route('client.kyc') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('client.kyc') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.kyc') }}</span>
                    </a>
                    <button type="button" x-on:click="$dispatch('open-modal', { name: 'edit-profile' })" class="w-full flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition text-texte-secondaire hover:text-texte-principal hover:bg-fond-card">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.profile') }}</span>
                    </button>
                    <a href="{{ route('client.security-settings') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('client.security-settings') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.security') }}</span>
                    </a>
                    <a href="{{ route('client.affiliate-dashboard') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition {{ $navItem('client.affiliate-dashboard') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 4v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"/></svg>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.affiliate') }}</span>
                    </a>
                </div>
            </div>

            {{-- Groupe SUPPORT --}}
            <div>
                <p class="px-2 mb-2 text-[10px] font-semibold uppercase tracking-wider text-texte-secondaire" x-show="sidebarExpanded">{{ __('app.client.nav_group_support') }}</p>
                <div class="space-y-1">
                    <a href="{{ route('client.notifications') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition relative {{ $navItem('client.notifications') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <span class="relative shrink-0">
                            <svg class="w-4.5 h-4.5" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadNotifications > 0)
                                <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-danger"></span>
                            @endif
                        </span>
                        <span x-show="sidebarExpanded" class="truncate flex-1">{{ __('app.dashboard.notifications') }}</span>
                        @if($unreadNotifications > 0)
                            <span x-show="sidebarExpanded" class="text-[10px] font-semibold rounded-full bg-danger/15 text-danger px-1.5 py-0.5">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                    <a href="{{ route('client.support') }}" class="flex items-center gap-3 px-2.5 py-2 rounded-full text-sm transition relative {{ $navItem('client.support') ? 'bg-couleur-principale/15 text-couleur-principale font-medium' : 'text-texte-secondaire hover:text-texte-principal hover:bg-fond-card' }}">
                        <span class="relative shrink-0">
                            <svg class="w-4.5 h-4.5" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.284 0-2.503-.24-3.605-.674L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            @if($openTickets > 0)
                                <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-couleur-principale"></span>
                            @endif
                        </span>
                        <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.support') }}</span>
                    </a>
                </div>
            </div>
        </nav>

        {{-- Mini-card profil --}}
        <div class="p-3 border-t border-bordure-subtile shrink-0">
            <div x-show="sidebarExpanded">
                <x-user-mini-card :user="$user" :show-edit-button="true" />
            </div>
            <div x-show="!sidebarExpanded" x-cloak class="flex justify-center">
                <button type="button" x-on:click="$dispatch('open-modal', { name: 'edit-profile' })" class="relative">
                    <div class="w-9 h-9 rounded-full bg-couleur-principale/15 flex items-center justify-center text-sm font-semibold text-couleur-principale overflow-hidden">
                        @if(!empty($user?->avatar_path))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            {{ \Illuminate\Support\Str::of($user?->name ?? '?')->substr(0, 1)->upper() }}
                        @endif
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-succes rounded-full border-2 border-fond-surface"></span>
                </button>
            </div>
            <button type="button" x-on:click="$dispatch('open-modal', { name: 'logout-confirm' })" class="mt-3 w-full flex items-center gap-3 px-2.5 py-2 rounded-full text-sm text-danger hover:bg-danger/10 transition">
                <svg class="w-4.5 h-4.5 shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span x-show="sidebarExpanded" class="truncate">{{ __('app.dashboard.logout') }}</span>
            </button>
        </div>
    </aside>

    {{-- Contenu principal --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Navbar --}}
        <header class="sticky top-0 z-30 h-16 flex items-center justify-between gap-4 px-4 lg:px-6 border-b border-bordure-subtile bg-fond-principal/95 backdrop-blur">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <button type="button" x-on:click="mobileOpen = true" class="lg:hidden text-texte-secondaire hover:text-texte-principal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="hidden sm:block w-full max-w-xs">
                    <x-search-input placeholder="{{ __('app.client.search_placeholder') }}" />
                </div>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <a href="{{ route('client.notifications') }}" class="relative text-texte-secondaire hover:text-texte-principal transition">
                    <svg class="w-5.5 h-5.5" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center text-[9px] font-bold rounded-full bg-danger text-white">{{ min($unreadNotifications, 9) }}{{ $unreadNotifications > 9 ? '+' : '' }}</span>
                    @endif
                </a>

                <x-user-menu-dropdown :user="$user" />
            </div>
        </header>

        <main class="flex-1 p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Modale globale: edition du profil (id 32) --}}
<x-modal name="edit-profile" max-width="lg">
    <livewire:client.edit-profile-form />
</x-modal>

{{-- Modale globale: confirmation de deconnexion (id 43) --}}
<x-modal name="logout-confirm" max-width="sm">
    <h3 class="font-display text-lg font-semibold text-texte-principal">{{ __('app.logout.confirm_title') }}</h3>
    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.logout.confirm_text', ['email' => $user?->email]) }}</p>
    <form method="POST" action="{{ route('logout') }}" class="mt-6 flex items-center justify-end gap-3">
        @csrf
        <button type="button" x-on:click="$dispatch('close-modal', { name: 'logout-confirm' })" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal text-sm font-medium px-4 py-2 transition">
            {{ __('app.common.cancel') }}
        </button>
        <button type="submit" class="inline-flex items-center rounded-sm bg-danger text-white text-sm font-semibold px-4 py-2 hover:brightness-110 transition">
            {{ __('app.logout.confirm_button') }}
        </button>
    </form>
</x-modal>

<x-toast-container />

@livewireScripts
</body>
</html>
