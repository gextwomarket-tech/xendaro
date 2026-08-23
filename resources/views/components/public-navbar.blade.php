@props(['siteIdentifier' => null])
<header x-data="{ mobileOpen: false }" class="sticky top-0 z-40 border-b border-bordure-subtile bg-fond-principal/90 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <x-logo size="sm" />

        <nav class="hidden lg:flex items-center gap-6 text-sm text-texte-secondaire">
            <a href="{{ url('/nos-services') }}" class="hover:text-texte-principal transition">{{ __('app.nav.our_services') }}</a>
            <a href="{{ url('/marches') }}" class="hover:text-texte-principal transition">{{ __('app.nav.markets') }}</a>
            <a href="{{ url('/academie') }}" class="hover:text-texte-principal transition">{{ __('app.nav.academy') }}</a>
            <a href="{{ url('/actualites') }}" class="hover:text-texte-principal transition">{{ __('app.nav.news') }}</a>
            <a href="{{ url('/faq') }}" class="hover:text-texte-principal transition">{{ __('app.nav.faq') }}</a>
            <a href="{{ url('/contact') }}" class="hover:text-texte-principal transition">{{ __('app.nav.contact') }}</a>
        </nav>

        <div class="hidden lg:flex items-center gap-3">
            <a href="{{ url('/connexion') }}" class="text-sm text-texte-secondaire hover:text-texte-principal transition">{{ __('app.nav.login') }}</a>
            <a href="{{ url('/inscription') }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2 hover:brightness-110 transition shadow-[0_0_0_1px_rgba(245,166,35,0.3)]">
                {{ __('app.nav.register') }}
            </a>
        </div>

        <button type="button" class="lg:hidden text-texte-principal" x-on:click="mobileOpen = !mobileOpen" aria-label="Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition class="lg:hidden border-t border-bordure-subtile bg-fond-surface px-4 py-4 space-y-3 text-sm">
        <a href="{{ url('/nos-services') }}" class="block text-texte-secondaire hover:text-texte-principal">{{ __('app.nav.our_services') }}</a>
        <a href="{{ url('/marches') }}" class="block text-texte-secondaire hover:text-texte-principal">{{ __('app.nav.markets') }}</a>
        <a href="{{ url('/academie') }}" class="block text-texte-secondaire hover:text-texte-principal">{{ __('app.nav.academy') }}</a>
        <a href="{{ url('/actualites') }}" class="block text-texte-secondaire hover:text-texte-principal">{{ __('app.nav.news') }}</a>
        <a href="{{ url('/faq') }}" class="block text-texte-secondaire hover:text-texte-principal">{{ __('app.nav.faq') }}</a>
        <a href="{{ url('/contact') }}" class="block text-texte-secondaire hover:text-texte-principal">{{ __('app.nav.contact') }}</a>
        <div class="pt-3 border-t border-bordure-subtile flex flex-col gap-2">
            <a href="{{ url('/connexion') }}" class="text-texte-secondaire">{{ __('app.nav.login') }}</a>
            <a href="{{ url('/inscription') }}" class="inline-flex justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-4 py-2">{{ __('app.nav.register') }}</a>
        </div>
    </div>
</header>
