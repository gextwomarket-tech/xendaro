@php
    $categories = [
        'forex' => 'Forex', 'crypto' => 'Crypto', 'metal' => 'Or / Métaux',
        'commodite' => 'Matières premières', 'indice' => 'Indices', 'action' => 'Actions',
    ];
@endphp
<x-layouts.public :title="$instrument->nom">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
        <a href="{{ url('/marches') }}" class="text-sm text-texte-secondaire hover:text-texte-principal transition inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            {{ __('app.market_detail.back_to_markets') }}
        </a>
        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">
                    {{ $instrument->nom }} <span class="text-texte-secondaire font-normal">({{ $instrument->symbole_interne }})</span>
                </h1>
                <p class="mt-1 text-sm text-texte-secondaire">{{ $categories[$instrument->categorie] ?? $instrument->categorie }}</p>
            </div>
            <a href="{{ url('/trade') }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-5 py-2.5 hover:brightness-110 transition">
                {{ __('app.market_detail.trade_cta') }}
            </a>
        </div>
    </section>

    {{-- Graphique --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <x-trading-chart :symbol="$instrument->symbole_provider_externe" />
    </section>

    {{-- Infos instrument --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-card-grid cols="4">
            <x-stat-card :label="__('app.market_detail.spread')" :value="(string) $instrument->spread" />
            <x-stat-card :label="__('app.market_detail.leverage')" value="1:{{ $instrument->levier_max }}" />
            <x-stat-card :label="__('app.market_detail.category')" :value="$categories[$instrument->categorie] ?? $instrument->categorie" />
            <x-stat-card :label="__('app.market_detail.provider')" :value="ucfirst($instrument->provider ?? '-')" />
        </x-card-grid>
        <div class="mt-6 rounded-sm bg-fond-card border border-bordure-subtile p-5">
            <p class="font-semibold text-texte-principal mb-1">{{ __('app.market_detail.market_hours') }}</p>
            <p class="text-sm text-texte-secondaire">{{ __('app.market_detail.market_hours_text') }}</p>
        </div>
    </section>

    {{-- Instruments lies --}}
    @if($related->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <h2 class="font-display text-xl font-semibold text-texte-principal mb-6">{{ __('app.markets.title') }}</h2>
            <x-card-grid cols="4">
                @foreach($related as $item)
                    <x-card-item href="{{ url('/marches/'.$item->symbole_interne) }}" :title="$item->nom" :description="$item->symbole_interne" />
                @endforeach
            </x-card-grid>
        </section>
    @endif

</x-layouts.public>
