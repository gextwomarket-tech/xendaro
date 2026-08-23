@php
    $tiers = config('affiliate.tiers', []);
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
@endphp
<x-layouts.public :title="__('app.affiliate.title')">

    <x-page-hero image="https://picsum.photos/seed/xendaro-affiliate-hero/1600/900" :eyebrow="__('app.affiliate.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.affiliate.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.affiliate.subtitle') }}</p>
    </x-page-hero>

    {{-- Presentation du programme : image superposee + texte --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-affiliate-partners/900/700" :alt="__('app.affiliate.intro_image_alt')" :rotate="2" />
                    <x-floating-badge position="bottom-right">
                        <p class="text-xs text-texte-secondaire uppercase tracking-wide">{{ __('app.affiliate.tier_3_label') }}</p>
                        <p class="text-lg font-display font-semibold text-couleur-principale">40%</p>
                    </x-floating-badge>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <h2 class="font-display text-2xl font-semibold text-texte-principal mb-3">{{ __('app.affiliate.intro_title') }}</h2>
                <p class="text-texte-secondaire leading-relaxed">{{ __('app.affiliate.intro') }}</p>
                <a href="{{ url('/inscription') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-5 py-2.5 hover:brightness-110 transition">
                    {{ __('app.affiliate.cta') }}
                </a>
            </x-reveal>
        </div>
    </section>

    {{-- Bareme de commissions - bande de stats animees --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-10 text-center">{{ __('app.affiliate.tiers_title') }}</h2>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            @forelse($tiers as $i => $tier)
                <x-reveal :delay="$i * 100">
                    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6">
                        <p class="text-texte-secondaire text-xs uppercase tracking-wide">{{ $tier['label'] }}</p>
                        <p class="mt-2 font-display font-bold text-couleur-principale">
                            <x-animated-counter :value="(int) $tier['commission']" suffix="%" class="text-4xl" />
                        </p>
                        <p class="mt-2 text-sm text-texte-secondaire">{{ $tier['range'] }}</p>
                    </div>
                </x-reveal>
            @empty
                <x-reveal :delay="0">
                    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6">
                        <p class="text-texte-secondaire text-xs uppercase tracking-wide">{{ __('app.affiliate.tier_1_label') }}</p>
                        <p class="mt-2 font-display font-bold text-couleur-principale"><x-animated-counter :value="20" suffix="%" class="text-4xl" /></p>
                        <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.affiliate.tier_1_value') }}</p>
                    </div>
                </x-reveal>
                <x-reveal :delay="100">
                    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6">
                        <p class="text-texte-secondaire text-xs uppercase tracking-wide">{{ __('app.affiliate.tier_2_label') }}</p>
                        <p class="mt-2 font-display font-bold text-couleur-principale"><x-animated-counter :value="30" suffix="%" class="text-4xl" /></p>
                        <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.affiliate.tier_2_value') }}</p>
                    </div>
                </x-reveal>
                <x-reveal :delay="200">
                    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6">
                        <p class="text-texte-secondaire text-xs uppercase tracking-wide">{{ __('app.affiliate.tier_3_label') }}</p>
                        <p class="mt-2 font-display font-bold text-couleur-principale"><x-animated-counter :value="40" suffix="%" class="text-4xl" /></p>
                        <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.affiliate.tier_3_value') }}</p>
                    </div>
                </x-reveal>
            @endforelse
        </div>
    </section>

    {{-- Comment ca marche : etapes numerotees --}}
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-10 text-center">{{ __('app.affiliate.how_it_works_title') }}</h2>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <x-reveal :delay="0" direction="up">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 rounded-full bg-couleur-principale/10 text-couleur-principale font-display font-bold flex items-center justify-center text-lg">1</div>
                    <p class="mt-4 font-semibold text-texte-principal">{{ __('app.affiliate.step_1_title') }}</p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.affiliate.step_1_desc') }}</p>
                </div>
            </x-reveal>
            <x-reveal :delay="120" direction="up">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 rounded-full bg-couleur-principale/10 text-couleur-principale font-display font-bold flex items-center justify-center text-lg">2</div>
                    <p class="mt-4 font-semibold text-texte-principal">{{ __('app.affiliate.step_2_title') }}</p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.affiliate.step_2_desc') }}</p>
                </div>
            </x-reveal>
            <x-reveal :delay="240" direction="up">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 rounded-full bg-couleur-principale/10 text-couleur-principale font-display font-bold flex items-center justify-center text-lg">3</div>
                    <p class="mt-4 font-semibold text-texte-principal">{{ __('app.affiliate.step_3_title') }}</p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.affiliate.step_3_desc') }}</p>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- Banniere full-bleed CTA --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="https://picsum.photos/seed/xendaro-affiliate-banner/1600/500" alt="" class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/70 via-fond-principal/90 to-fond-principal"></div>
        </div>
        <x-reveal direction="scale">
            <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.affiliate.banner_title') }}</h2>
                <p class="mt-3 text-texte-secondaire">{{ __('app.affiliate.banner_text') }}</p>
                <a href="{{ url('/inscription') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.affiliate.cta') }}
                </a>
            </div>
        </x-reveal>
    </section>

    <x-floating-button href="{{ url('/inscription') }}" aria-label="{{ __('app.affiliate.cta') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg>
    </x-floating-button>

</x-layouts.public>
