{{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
<x-layouts.public :title="__('app.services.title')">

    <x-page-hero image="/images/trading/trading-15.jpg" :eyebrow="__('app.services.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.services.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.services.subtitle') }}</p>
    </x-page-hero>

    {{-- 1. Full-bleed banner (pattern D) --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="/images/trading/trading-12.jpg" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-fond-principal via-fond-principal/85 to-fond-principal/50"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <x-reveal direction="scale">
                <h2 class="font-display text-2xl sm:text-4xl font-bold text-texte-principal">{{ __('app.services.banner_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed max-w-2xl mx-auto">{{ __('app.services.banner_body') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- 2. Teasers vers les 3 sous-pages, chacune avec photo (split alterne, pattern A x3) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="/images/trading/trading-13.jpg" :alt="__('app.services.account_types_title')" />
                    <div class="relative">
                        <x-floating-badge position="bottom-left">
                            <p class="text-xs text-texte-secondaire">{{ __('app.account_types.table_deposit') }}</p>
                            <p class="font-display text-lg font-bold text-couleur-principale">$0</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <h3 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.services.account_types_title') }}</h3>
                <p class="mt-3 text-texte-secondaire leading-relaxed">{{ __('app.services.account_types_desc') }}</p>
                <a href="{{ url('/nos-services/types-de-comptes') }}" class="mt-5 inline-flex items-center gap-2 text-couleur-principale font-semibold hover:underline">
                    {{ __('app.services.discover') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </x-reveal>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left" class="order-2 lg:order-1">
                <h3 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.services.platforms_title') }}</h3>
                <p class="mt-3 text-texte-secondaire leading-relaxed">{{ __('app.services.platforms_desc') }}</p>
                <a href="{{ url('/nos-services/plateformes') }}" class="mt-5 inline-flex items-center gap-2 text-couleur-principale font-semibold hover:underline">
                    {{ __('app.services.discover') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </x-reveal>
            <x-reveal direction="right" :delay="100" class="order-1 lg:order-2">
                <div class="relative">
                    <x-photo-card src="/images/trading/trading-04.jpg" :alt="__('app.services.platforms_title')" :rotate="-2" />
                    <div class="relative">
                        <x-floating-badge position="top-right">
                            <p class="text-xs text-texte-secondaire">{{ __('app.platforms.mobile_badge') }}</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="/images/trading/trading-10.jpg" :alt="__('app.services.trading_conditions_title')" :rotate="2" />
                    <div class="relative">
                        <x-floating-badge position="bottom-right">
                            <p class="text-xs text-texte-secondaire">{{ __('app.account_types.table_spread') }}</p>
                            <p class="font-display text-lg font-bold text-couleur-principale">0.0</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <h3 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.services.trading_conditions_title') }}</h3>
                <p class="mt-3 text-texte-secondaire leading-relaxed">{{ __('app.services.trading_conditions_desc') }}</p>
                <a href="{{ url('/nos-services/conditions-de-trading') }}" class="mt-5 inline-flex items-center gap-2 text-couleur-principale font-semibold hover:underline">
                    {{ __('app.services.discover') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </x-reveal>
        </div>
    </section>

    {{-- 3. Trust strip (pattern H) --}}
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-reveal>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <span class="rounded-full border border-bordure-subtile bg-fond-card px-4 py-2 text-sm text-texte-secondaire">{{ __('app.services.trust_1') }}</span>
                <span class="rounded-full border border-bordure-subtile bg-fond-card px-4 py-2 text-sm text-texte-secondaire">{{ __('app.services.trust_2') }}</span>
                <span class="rounded-full border border-bordure-subtile bg-fond-card px-4 py-2 text-sm text-texte-secondaire">{{ __('app.services.trust_3') }}</span>
                <span class="rounded-full border border-bordure-subtile bg-fond-card px-4 py-2 text-sm text-texte-secondaire">{{ __('app.services.trust_4') }}</span>
            </div>
        </x-reveal>
    </section>

    {{-- Contenu nos_services (site_identifier) --}}
    @if($siteIdentifier?->nos_services)
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <x-reveal>
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.services.intro_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl font-bold text-texte-principal mb-4">{{ __('app.services.intro_title') }}</h2>
                <div class="rounded-sm bg-fond-card border border-bordure-subtile p-8 prose prose-invert max-w-none text-texte-secondaire">
                    @if(strip_tags($siteIdentifier->nos_services) === $siteIdentifier->nos_services)
                        <p>{!! nl2br(e($siteIdentifier->nos_services)) !!}</p>
                    @else
                        {!! $siteIdentifier->nos_services !!}
                    @endif
                </div>
            </x-reveal>
        </section>
    @endif

    <x-floating-button href="{{ url('/contact') }}" aria-label="{{ __('app.floating.support') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
