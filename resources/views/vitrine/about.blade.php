{{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
<x-layouts.public :title="__('app.about.title')">

    <x-page-hero image="https://picsum.photos/seed/xendaro-about-hero/1600/900" :eyebrow="__('app.about.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.about.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.about.subtitle') }}</p>
    </x-page-hero>

    {{-- 1. Notre mission - split image/text (pattern A) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-about-team/900/1100" :alt="__('app.about.team_title')" ratio="aspect-[4/5]" />
                    <div class="relative">
                        <x-floating-badge position="bottom-right">
                            <p class="text-xs text-texte-secondaire">{{ __('app.about.milestone_1_year') }}</p>
                            <p class="font-display text-sm font-bold text-couleur-principale">{{ __('app.about.milestone_1_title') }}</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.about.story_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.about.story_title') }}</h2>

                @if($siteIdentifier?->about_us)
                    <div class="mt-4 prose prose-invert max-w-none text-texte-secondaire leading-relaxed">
                        @if(strip_tags($siteIdentifier->about_us) === $siteIdentifier->about_us)
                            <p>{!! nl2br(e($siteIdentifier->about_us)) !!}</p>
                        @else
                            {!! $siteIdentifier->about_us !!}
                        @endif
                    </div>
                @endif
            </x-reveal>
        </div>
    </section>

    {{-- 2. Chiffres cles - stat strip (pattern B) --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <p class="text-center text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-8">{{ __('app.about.stats_eyebrow') }}</p>
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

    {{-- 3. Notre parcours - timeline (pattern G) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <div class="text-center max-w-2xl mx-auto mb-12">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.about.timeline_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.about.timeline_title') }}</h2>
            </div>
        </x-reveal>
        @php
            $milestones = [
                ['year' => __('app.about.milestone_1_year'), 'title' => __('app.about.milestone_1_title'), 'desc' => __('app.about.milestone_1_desc')],
                ['year' => __('app.about.milestone_2_year'), 'title' => __('app.about.milestone_2_title'), 'desc' => __('app.about.milestone_2_desc')],
                ['year' => __('app.about.milestone_3_year'), 'title' => __('app.about.milestone_3_title'), 'desc' => __('app.about.milestone_3_desc')],
                ['year' => __('app.about.milestone_4_year'), 'title' => __('app.about.milestone_4_title'), 'desc' => __('app.about.milestone_4_desc')],
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($milestones as $i => $m)
                <x-reveal :delay="$i * 100">
                    <div class="relative pl-6 sm:pl-0 sm:pt-6 border-l-2 sm:border-l-0 sm:border-t-2 border-couleur-principale/30">
                        <div class="absolute -left-[9px] top-0 sm:left-0 sm:-top-[9px] w-4 h-4 rounded-full bg-couleur-principale"></div>
                        <p class="font-display text-2xl font-bold text-couleur-principale">{{ $m['year'] }}</p>
                        <p class="mt-1 font-semibold text-texte-principal">{{ $m['title'] }}</p>
                        <p class="mt-2 text-sm text-texte-secondaire leading-relaxed">{{ $m['desc'] }}</p>
                    </div>
                </x-reveal>
            @endforeach
        </div>
    </section>

    {{-- 4. Notre equipe - full-bleed banner (pattern D) --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="https://picsum.photos/seed/xendaro-about-office/1920/700" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-fond-principal via-fond-principal/85 to-fond-principal/40"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <x-reveal direction="scale">
                <p class="inline-flex items-center justify-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.about.team_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-4xl font-bold text-texte-principal">{{ __('app.about.team_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed max-w-xl mx-auto">{{ __('app.about.team_body') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- 5. Valeurs - feature grid (pattern C) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-8 text-center">{{ __('app.about.values_title') }}</h2>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-reveal :delay="0"><x-icon-feature :title="__('app.about.value_1_title')" :description="__('app.about.value_1_desc')" /></x-reveal>
            <x-reveal :delay="80"><x-icon-feature :title="__('app.about.value_2_title')" :description="__('app.about.value_2_desc')" /></x-reveal>
            <x-reveal :delay="160"><x-icon-feature :title="__('app.about.value_3_title')" :description="__('app.about.value_3_desc')" /></x-reveal>
            <x-reveal :delay="240"><x-icon-feature :title="__('app.about.value_4_title')" :description="__('app.about.value_4_desc')" /></x-reveal>
        </div>
    </section>

    <x-floating-button href="{{ url('/contact') }}" aria-label="{{ __('app.floating.support') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
