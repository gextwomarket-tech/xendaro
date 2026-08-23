{{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
<x-layouts.public>

    {{-- Hero --}}
    <x-page-hero image="https://picsum.photos/seed/xendaro-home-hero/1920/1080" :eyebrow="__('app.home.hero_eyebrow')">
        <h1 class="font-display text-4xl sm:text-6xl font-bold text-texte-principal max-w-3xl mx-auto">
            {{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}
        </h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">
            {{ $siteIdentifier->slogan ?? '' }}
        </p>
        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="{{ url('/inscription') }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 hover:shadow-[0_0_24px_rgba(245,166,35,0.35)] transition">
                {{ __('app.nav.register') }}
            </a>
            <a href="{{ url('/nos-services') }}" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-principal font-semibold px-6 py-3 hover:border-couleur-principale/50 transition">
                {{ __('app.nav.our_services') }}
            </a>
        </div>
    </x-page-hero>

    {{-- 1. Stat strip (pattern B) --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="font-display text-3xl sm:text-4xl font-bold text-couleur-principale"><x-animated-counter :value="12400" suffix="+" /></p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.home.stat_traders') }}</p>
                </div>
                <div>
                    <p class="font-display text-3xl sm:text-4xl font-bold text-couleur-principale"><x-animated-counter :value="\App\Models\MarketInstrument::where('est_actif', true)->count()" /></p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.home.stat_instruments') }}</p>
                </div>
                <div>
                    <p class="font-display text-3xl sm:text-4xl font-bold text-couleur-principale"><x-animated-counter :value="85" prefix="$" suffix="M+" /></p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.home.stat_volume') }}</p>
                </div>
                <div>
                    <p class="font-display text-3xl sm:text-4xl font-bold text-couleur-principale">24/7</p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.home.stat_support') }}</p>
                </div>
            </div>
        </x-reveal>
    </section>

    {{-- 2. Marches - split image/text (pattern A) + card-grid teaser --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-home-markets/900/1100" :alt="__('app.home.markets_title')" ratio="aspect-[4/5]" />
                    <div class="relative">
                        <x-floating-badge position="bottom-right">
                            <p class="text-xs text-texte-secondaire">{{ __('app.home.stat_instruments') }}</p>
                            <p class="font-display text-xl font-bold text-couleur-principale">{{ \App\Models\MarketInstrument::where('est_actif', true)->count() }}+</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.home.markets_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.home.markets_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.home.markets_body') }}</p>
                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <x-card-item href="{{ url('/marches?categorie=forex') }}" :title="__('app.home.cat_forex_title')" :description="__('app.home.cat_forex_desc')" />
                    <x-card-item href="{{ url('/marches?categorie=crypto') }}" :title="__('app.home.cat_crypto_title')" :description="__('app.home.cat_crypto_desc')" />
                    <x-card-item href="{{ url('/marches?categorie=metal') }}" :title="__('app.home.cat_metal_title')" :description="__('app.home.cat_metal_desc')" />
                    <x-card-item href="{{ url('/marches?categorie=indice') }}" :title="__('app.home.cat_indice_title')" :description="__('app.home.cat_indice_desc')" />
                    <x-card-item href="{{ url('/marches?categorie=commodite') }}" :title="__('app.home.cat_commodite_title')" :description="__('app.home.cat_commodite_desc')" />
                    <x-card-item href="{{ url('/marches?categorie=action') }}" :title="__('app.home.cat_action_title')" :description="__('app.home.cat_action_desc')" />
                </div>
                <a href="{{ url('/marches') }}" class="mt-6 inline-flex items-center gap-2 text-couleur-principale font-semibold hover:underline">
                    {{ __('app.home.markets_cta') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </x-reveal>
        </div>
    </section>

    {{-- 3. Pourquoi Xendaro Fox - feature grid (pattern C) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <div class="text-center max-w-2xl mx-auto mb-12">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.home.why_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.home.why_title') }}</h2>
                <p class="mt-4 text-texte-secondaire">{{ __('app.home.why_body') }}</p>
            </div>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-reveal :delay="0">
                <x-icon-feature :title="__('app.home.why_1_title')" :description="__('app.home.why_1_desc')">
                    <x-slot:icon><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="80">
                <x-icon-feature :title="__('app.home.why_2_title')" :description="__('app.home.why_2_desc')">
                    <x-slot:icon><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="160">
                <x-icon-feature :title="__('app.home.why_3_title')" :description="__('app.home.why_3_desc')">
                    <x-slot:icon><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414 1.414A9 9 0 105.636 18.364l1.414-1.414M12 8v4l3 3" /></svg></x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="240">
                <x-icon-feature :title="__('app.home.why_4_title')" :description="__('app.home.why_4_desc')">
                    <x-slot:icon><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></x-slot:icon>
                </x-icon-feature>
            </x-reveal>
        </div>
    </section>

    {{-- 4. Plateformes - full-bleed banner (pattern D) --}}
    <section class="relative overflow-hidden py-20 my-14">
        <div class="absolute inset-0 -z-10">
            <img src="https://picsum.photos/seed/xendaro-home-platforms/1920/700" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-fond-principal via-fond-principal/85 to-fond-principal/40"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal direction="scale">
                <div class="max-w-xl">
                    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                        {{ __('app.home.platforms_eyebrow') }}
                    </p>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.home.platforms_title') }}</h2>
                    <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.home.platforms_body') }}</p>
                    <a href="{{ url('/nos-services/plateformes') }}" class="mt-8 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                        {{ __('app.home.platforms_cta') }}
                    </a>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- 5. Types de comptes - split image/text alterne (pattern A, image a droite) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left" class="order-2 lg:order-1">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.home.accounts_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.home.accounts_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.home.accounts_body') }}</p>
                <div class="mt-6 space-y-3">
                    <x-icon-feature :title="__('app.home.accounts_1_title')" :description="__('app.home.accounts_1_desc')" />
                    <x-icon-feature :title="__('app.home.accounts_2_title')" :description="__('app.home.accounts_2_desc')" />
                    <x-icon-feature :title="__('app.home.accounts_3_title')" :description="__('app.home.accounts_3_desc')" />
                </div>
                <a href="{{ url('/nos-services/types-de-comptes') }}" class="mt-6 inline-flex items-center gap-2 text-couleur-principale font-semibold hover:underline">
                    {{ __('app.home.accounts_cta') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </x-reveal>
            <x-reveal direction="right" :delay="100" class="order-1 lg:order-2">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-home-accounts/900/1100" :alt="__('app.home.accounts_title')" ratio="aspect-[4/5]" :rotate="2" />
                    <div class="relative">
                        <x-floating-badge position="top-left">
                            <p class="text-xs text-texte-secondaire">{{ __('app.account_types.table_leverage') }}</p>
                            <p class="font-display text-xl font-bold text-couleur-principale">1:500</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- 6. Academie - split image/text (pattern A, variation compacte) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="rounded-lg border border-bordure-subtile bg-fond-card overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <x-reveal direction="scale">
                    <img src="https://picsum.photos/seed/xendaro-home-academy/1000/800" alt="{{ __('app.home.academy_title') }}" loading="lazy" class="w-full h-64 lg:h-full object-cover">
                </x-reveal>
                <x-reveal direction="right" :delay="100">
                    <div class="p-8 sm:p-12">
                        <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                            {{ __('app.home.academy_eyebrow') }}
                        </p>
                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.home.academy_title') }}</h2>
                        <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.home.academy_body') }}</p>
                        <a href="{{ url('/academie') }}" class="mt-6 inline-flex items-center rounded-sm border border-bordure-subtile text-texte-principal font-semibold px-5 py-2.5 hover:border-couleur-principale/50 transition">
                            {{ __('app.home.academy_cta') }}
                        </a>
                    </div>
                </x-reveal>
            </div>
        </div>
    </section>

    {{-- 7. Temoignages (pattern F) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <div class="text-center max-w-2xl mx-auto mb-12">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.home.testimonials_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.home.testimonials_title') }}</h2>
            </div>
        </x-reveal>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @php
                $testimonials = [
                    ['quote' => __('app.home.t1_quote'), 'name' => __('app.home.t1_name'), 'role' => __('app.home.t1_role'), 'seed' => 'xendaro-home-avatar-1'],
                    ['quote' => __('app.home.t2_quote'), 'name' => __('app.home.t2_name'), 'role' => __('app.home.t2_role'), 'seed' => 'xendaro-home-avatar-2'],
                    ['quote' => __('app.home.t3_quote'), 'name' => __('app.home.t3_name'), 'role' => __('app.home.t3_role'), 'seed' => 'xendaro-home-avatar-3'],
                ];
            @endphp
            @foreach($testimonials as $i => $t)
                <x-reveal :delay="$i * 100">
                    <div class="relative pt-8">
                        <div class="rounded-lg bg-fond-card border border-bordure-subtile p-6 pt-10">
                            <svg class="w-8 h-8 text-couleur-principale/30 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z" /></svg>
                            <p class="italic text-texte-secondaire leading-relaxed">&laquo; {{ $t['quote'] }} &raquo;</p>
                            <p class="mt-4 font-semibold text-texte-principal">{{ $t['name'] }}</p>
                            <p class="text-xs text-texte-secondaire">{{ $t['role'] }}</p>
                        </div>
                        <img src="https://picsum.photos/seed/{{ $t['seed'] }}/120/120" alt="{{ $t['name'] }}" loading="lazy" class="absolute top-0 left-6 w-14 h-14 rounded-full border-4 border-fond-principal object-cover shadow-lg">
                    </div>
                </x-reveal>
            @endforeach
        </div>
    </section>

    {{-- 8. Parrainage / promotions - banner teaser (pattern D, ne duplique pas le CTA global du footer) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-14">
        <x-reveal direction="scale">
            <div class="relative overflow-hidden rounded-lg border border-bordure-subtile">
                <img src="https://picsum.photos/seed/xendaro-home-affiliate/1600/500" alt="" class="w-full h-56 sm:h-64 object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-fond-principal via-fond-principal/80 to-transparent flex items-center">
                    <div class="px-8 sm:px-12 max-w-lg">
                        <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-2">
                            {{ __('app.home.final_eyebrow') }}
                        </p>
                        <h2 class="font-display text-xl sm:text-2xl font-bold text-texte-principal">{{ __('app.home.final_title') }}</h2>
                        <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.home.final_body') }}</p>
                        <a href="{{ url('/parrainage') }}" class="mt-5 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-5 py-2.5 text-sm hover:brightness-110 transition">
                            {{ __('app.home.final_cta') }}
                        </a>
                    </div>
                </div>
            </div>
        </x-reveal>
    </section>

    <x-floating-button href="{{ url('/contact') }}" aria-label="{{ __('app.floating.support') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
