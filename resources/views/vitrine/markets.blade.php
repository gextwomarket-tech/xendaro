@php
    // Variation 24h simulee (MVP, voir xendaro-fox-plan.json > markets), deterministe par instrument + jour
    // pour rester stable pendant toute la journee (n'impacte pas MarketPriceService, dedie a la page Trade).
    $change24h = function (\App\Models\MarketInstrument $instrument): float {
        $hash = crc32($instrument->symbole_interne.'|'.now()->format('Y-m-d'));
        return round((($hash % 601) - 300) / 100, 2); // -3.00% a +3.00%
    };
@endphp
<x-layouts.public :title="__('app.markets.title')">

    <x-page-hero image="https://picsum.photos/seed/xendaro-markets-hero/1600/900" :eyebrow="__('app.markets.title')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.markets.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.markets.subtitle') }}</p>
    </x-page-hero>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <x-reveal>
            <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between rounded-sm bg-fond-card border border-bordure-subtile p-4">
                <div class="w-full sm:max-w-xs">
                    <x-search-input name="search" value="{{ $search }}" :placeholder="__('app.markets.search_placeholder')" />
                </div>
                <x-select-filter
                    name="categorie"
                    onchange="this.form.submit()"
                    :options="$categories"
                    :selected="$categorie"
                    :placeholder="__('app.markets.filter_all')"
                />
                <button type="submit" class="hidden">{{ __('app.common.filter') }}</button>
            </form>
        </x-reveal>
    </section>

    {{-- Grille de graphs live par instrument, filtree en direct par le formulaire ci-dessus --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($instruments as $i => $instrument)
                @php $variation = $change24h($instrument); @endphp
                <x-reveal :delay="($i % 3) * 80">
                    <a href="{{ url('/marches/'.$instrument->symbole_interne) }}" class="group block rounded-sm bg-fond-card border border-bordure-subtile hover:border-couleur-principale/50 transition overflow-hidden">
                        <div class="flex items-center justify-between px-4 pt-4">
                            <div>
                                <p class="font-display font-semibold text-texte-principal group-hover:text-couleur-principale transition">{{ $instrument->nom }}</p>
                                <p class="text-xs text-texte-secondaire">{{ $instrument->symbole_interne }} &middot; {{ $categories[$instrument->categorie] ?? $instrument->categorie }}</p>
                            </div>
                            <div class="text-right">
                                <p class="tabular-nums text-texte-principal font-medium">{{ number_format((float) $instrument->prix_reference, 5) }}</p>
                                <p class="tabular-nums text-xs {{ $variation >= 0 ? 'text-succes' : 'text-danger' }}">
                                    {{ $variation >= 0 ? '+' : '' }}{{ $variation }}%
                                </p>
                            </div>
                        </div>
                        @if($instrument->symbole_provider_externe)
                            <div class="mt-2 pointer-events-none">
                                <x-mini-chart :symbol="$instrument->symbole_provider_externe" :height="140" />
                            </div>
                        @else
                            <div class="mt-2 h-[140px] flex items-center justify-center text-xs text-texte-secondaire">{{ __('app.market_detail.chart_unavailable') }}</div>
                        @endif
                    </a>
                </x-reveal>
            @empty
                <p class="col-span-full text-center text-texte-secondaire py-12">{{ __('app.common.no_results') }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $instruments->links() }}</div>
    </section>

</x-layouts.public>
