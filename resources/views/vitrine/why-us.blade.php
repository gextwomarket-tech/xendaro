{{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
<x-layouts.public :title="__('app.why_us.title')">

    <x-page-hero image="https://picsum.photos/seed/xendaro-security-hero/1600/900" :eyebrow="__('app.why_us.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.why_us.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.why_us.subtitle') }}</p>
    </x-page-hero>

    {{-- 1. Full-bleed trust banner (pattern D) --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="https://picsum.photos/seed/xendaro-security-banner/1920/700" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-fond-principal via-fond-principal/85 to-fond-principal/50"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <x-reveal direction="scale">
                <h2 class="font-display text-2xl sm:text-4xl font-bold text-texte-principal">{{ __('app.why_us.banner_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed max-w-xl mx-auto">{{ __('app.why_us.banner_body') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- 2. Arguments de confiance - feature grid (pattern C) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <p class="text-center text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-8">{{ __('app.why_us.features_eyebrow') }}</p>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-reveal :delay="0">
                <x-icon-feature :title="__('app.why_us.security_title')" :description="__('app.why_us.security_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="80">
                <x-icon-feature :title="__('app.why_us.execution_title')" :description="__('app.why_us.execution_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="160">
                <x-icon-feature :title="__('app.why_us.support_title')" :description="__('app.why_us.support_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414 1.414A9 9 0 105.636 18.364l1.414-1.414M12 8v4l3 3" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="240">
                <x-icon-feature :title="__('app.why_us.regulation_title')" :description="__('app.why_us.regulation_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="320">
                <x-icon-feature :title="__('app.why_us.privacy_title')" :description="__('app.why_us.privacy_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
        </div>
    </section>

    {{-- 3. KYC - split image/text (pattern A) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-security-kyc/900/700" :alt="__('app.why_us.kyc_title')" :rotate="-2" />
                    <div class="relative">
                        <x-floating-badge position="bottom-right">
                            <p class="text-xs text-texte-secondaire">KYC</p>
                            <p class="font-display text-lg font-bold text-couleur-principale">100%</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.why_us.kyc_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.why_us.kyc_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.why_us.kyc_body') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- 4. Texte securite/regulation/donnees --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <x-reveal>
            <div class="rounded-sm bg-fond-card border border-bordure-subtile p-8">
                <h2 class="font-display text-xl font-semibold text-texte-principal mb-3">{{ __('app.why_us.text_title') }}</h2>
                <p class="text-texte-secondaire leading-relaxed">{{ __('app.why_us.text_body') }}</p>
            </div>
        </x-reveal>
    </section>

</x-layouts.public>
