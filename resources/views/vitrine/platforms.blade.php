{{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
<x-layouts.public :title="__('app.platforms.title')">

    <x-page-hero image="https://picsum.photos/seed/xendaro-platforms-hero/1600/900" :eyebrow="__('app.platforms.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.platforms.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.platforms.subtitle') }}</p>
    </x-page-hero>

    {{-- 1. WebTrader - split image/text (pattern A) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-platforms-webtrader/1000/750" :alt="__('app.platforms.webtrader_title')" ratio="aspect-[4/3]" />
                    <div class="relative">
                        <x-floating-badge position="bottom-left">
                            <p class="text-xs text-texte-secondaire">{{ __('app.platforms.webtrader_badge') }}</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.platforms.webtrader_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.platforms.webtrader_desc') }}</p>
                <p class="mt-3 text-texte-secondaire leading-relaxed">{{ __('app.platforms.webtrader_body') }}</p>
                <a href="{{ url('/trade') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.platforms.webtrader_cta') }}
                </a>
            </x-reveal>
        </div>
    </section>

    {{-- 2. Mobile - full-bleed banner (pattern D) --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="https://picsum.photos/seed/xendaro-platforms-mobile/1920/700" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-l from-fond-principal via-fond-principal/85 to-fond-principal/40"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal direction="scale">
                <div class="max-w-xl ml-auto text-right">
                    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3 justify-end">
                        <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                        {{ __('app.platforms.mobile_badge') }}
                    </p>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.platforms.mobile_title') }}</h2>
                    <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.platforms.mobile_desc') }}</p>
                    <p class="mt-2 text-texte-secondaire leading-relaxed">{{ __('app.platforms.mobile_body') }}</p>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- 3. Desktop - split image/text inverse (pattern A) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left" class="order-2 lg:order-1">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.platforms.desktop_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.platforms.desktop_desc') }}</p>
                <p class="mt-3 text-texte-secondaire leading-relaxed">{{ __('app.platforms.desktop_body') }}</p>
                <p class="mt-4 inline-flex items-center gap-2 text-sm text-texte-secondaire">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale"></span>
                    {{ __('app.platforms.desktop_badge') }}
                </p>
            </x-reveal>
            <x-reveal direction="right" :delay="100" class="order-1 lg:order-2">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-platforms-desktop/1000/750" :alt="__('app.platforms.desktop_title')" ratio="aspect-[4/3]" :rotate="2" />
                    <div class="relative">
                        <x-floating-badge position="top-right">
                            <p class="text-xs text-texte-secondaire">{{ __('app.platforms.desktop_badge') }}</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- 4. Comparatif rapide - feature grid (pattern C) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <div class="text-center max-w-2xl mx-auto mb-10">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.platforms.compare_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.platforms.compare_title') }}</h2>
            </div>
        </x-reveal>
        <x-card-grid cols="3">
            <x-reveal :delay="0">
                <x-card-item :title="__('app.platforms.webtrader_title')" :description="__('app.platforms.webtrader_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </x-slot:icon>
                </x-card-item>
            </x-reveal>
            <x-reveal :delay="80">
                <x-card-item :title="__('app.platforms.mobile_title')" :description="__('app.platforms.mobile_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </x-slot:icon>
                </x-card-item>
            </x-reveal>
            <x-reveal :delay="160">
                <x-card-item :title="__('app.platforms.desktop_title')" :description="__('app.platforms.desktop_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </x-slot:icon>
                </x-card-item>
            </x-reveal>
        </x-card-grid>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 text-center">
        <x-reveal>
            <a href="{{ url('/trade') }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                {{ __('app.platforms.webtrader_cta') }}
            </a>
        </x-reveal>
    </section>

</x-layouts.public>
