@php
    $categories = [
        'forex' => 'Forex', 'crypto' => 'Crypto', 'metal' => 'Or / Métaux',
        'commodite' => 'Matières premières', 'indice' => 'Indices', 'action' => 'Actions',
    ];
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
@endphp
<x-layouts.public :title="$instrument->nom">

    <x-page-hero image="https://picsum.photos/seed/xendaro-market-detail-{{ $instrument->categorie }}/1600/700" :eyebrow="__('app.market_detail.hero_eyebrow')" align="left" size="sm">
        <a href="{{ url('/marches') }}" class="text-sm text-texte-secondaire hover:text-texte-principal transition inline-flex items-center gap-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            {{ __('app.market_detail.back_to_markets') }}
        </a>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl sm:text-4xl font-bold text-texte-principal">
                    {{ $instrument->nom }} <span class="text-texte-secondaire font-normal text-lg sm:text-xl">({{ $instrument->symbole_interne }})</span>
                </h1>
                <p class="mt-1 text-sm text-texte-secondaire">{{ $categories[$instrument->categorie] ?? $instrument->categorie }}</p>
            </div>
            <a href="{{ url('/trade') }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-5 py-2.5 hover:brightness-110 transition shrink-0">
                {{ __('app.market_detail.trade_cta') }}
            </a>
        </div>
    </x-page-hero>

    {{-- Graphique en direct - composant central de la page (voir x-trading-chart, non modifie) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <x-reveal direction="scale">
            <x-trading-chart :symbol="$instrument->symbole_provider_externe" />
        </x-reveal>
    </section>

    {{-- Caracteristiques de l'instrument : image superposee + stats --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="https://picsum.photos/seed/xendaro-market-detail-analysis/900/700" :alt="$instrument->nom" :rotate="-2" />
                    <x-floating-badge position="bottom-right">
                        <p class="text-xs text-texte-secondaire uppercase tracking-wide">{{ __('app.market_detail.spread') }}</p>
                        <p class="text-lg font-display font-semibold text-couleur-principale">{{ $instrument->spread }}</p>
                    </x-floating-badge>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <h2 class="font-display text-2xl font-semibold text-texte-principal mb-3">{{ __('app.market_detail.quick_stats_title') }}</h2>
                <p class="text-texte-secondaire leading-relaxed mb-6">{{ __('app.market_detail.detail_intro') }}</p>
                <x-card-grid cols="2">
                    <x-stat-card :label="__('app.market_detail.spread')" :value="(string) $instrument->spread" />
                    <x-stat-card :label="__('app.market_detail.leverage')" value="1:{{ $instrument->levier_max }}" />
                    <x-stat-card :label="__('app.market_detail.category')" :value="$categories[$instrument->categorie] ?? $instrument->categorie" />
                    <x-stat-card :label="__('app.market_detail.provider')" :value="ucfirst($instrument->provider ?? '-')" />
                </x-card-grid>
                <div class="mt-6 rounded-sm bg-fond-card border border-bordure-subtile p-5">
                    <p class="font-semibold text-texte-principal mb-1">{{ __('app.market_detail.market_hours') }}</p>
                    <p class="text-sm text-texte-secondaire">{{ __('app.market_detail.market_hours_text') }}</p>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- Banniere full-bleed CTA --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="https://picsum.photos/seed/xendaro-market-detail-banner/1600/500" alt="" class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/70 via-fond-principal/90 to-fond-principal"></div>
        </div>
        <x-reveal direction="scale">
            <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.market_detail.banner_title') }}</h2>
                <p class="mt-3 text-texte-secondaire">{{ __('app.market_detail.banner_text', ['name' => $instrument->nom]) }}</p>
                <a href="{{ url('/trade') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.market_detail.trade_cta') }}
                </a>
            </div>
        </x-reveal>
    </section>

    {{-- Instruments lies - mini graphs live --}}
    @if($related->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <x-reveal>
                <h2 class="font-display text-xl font-semibold text-texte-principal mb-1">{{ __('app.market_detail.related_title') }}</h2>
                <p class="text-sm text-texte-secondaire mb-6">{{ __('app.market_detail.related_subtitle') }}</p>
            </x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $i => $item)
                    <x-reveal :delay="($i % 4) * 80">
                        <a href="{{ url('/marches/'.$item->symbole_interne) }}" class="group block rounded-sm bg-fond-card border border-bordure-subtile hover:border-couleur-principale/50 transition overflow-hidden">
                            <div class="px-4 pt-4">
                                <p class="font-display font-semibold text-sm text-texte-principal group-hover:text-couleur-principale transition">{{ $item->nom }}</p>
                                <p class="text-xs text-texte-secondaire">{{ $item->symbole_interne }}</p>
                            </div>
                            @if($item->symbole_provider_externe)
                                <div class="mt-2 pointer-events-none">
                                    <x-mini-chart :symbol="$item->symbole_provider_externe" :height="120" />
                                </div>
                            @else
                                <div class="mt-2 h-[120px] flex items-center justify-center text-xs text-texte-secondaire">{{ __('app.market_detail.chart_unavailable') }}</div>
                            @endif
                        </a>
                    </x-reveal>
                @endforeach
            </div>
        </section>
    @endif

</x-layouts.public>
