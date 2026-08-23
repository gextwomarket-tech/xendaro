{{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
@php
    $categories = [
        'forex' => 'Forex', 'crypto' => 'Crypto', 'metal' => 'Or / Métaux',
        'commodite' => 'Matières premières', 'indice' => 'Indices', 'action' => 'Actions',
    ];
    $selected = request()->query('categorie');
    $instruments = \App\Models\MarketInstrument::where('est_actif', true)
        ->when($selected, fn ($q) => $q->where('categorie', $selected))
        ->orderBy('categorie')->orderBy('nom')
        ->paginate(15)
        ->withQueryString();
@endphp
<x-layouts.public :title="__('app.trading_conditions.title')">

    <x-page-hero image="/images/trading/trading-13.jpg" :eyebrow="__('app.trading_conditions.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.trading_conditions.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.trading_conditions.subtitle') }}</p>
    </x-page-hero>

    {{-- 1. Intro split image/text (pattern A) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="/images/trading/trading-15.jpg" :alt="__('app.trading_conditions.title')" :rotate="-2" />
                    <div class="relative">
                        <x-floating-badge position="bottom-right">
                            <p class="text-xs text-texte-secondaire">{{ __('app.trading_conditions.table_spread') }}</p>
                            <p class="font-display text-lg font-bold text-couleur-principale">{{ __('app.services.trust_3') }}</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.trading_conditions.intro_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.trading_conditions.intro_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.trading_conditions.intro_body') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- 2. Filtre + tableau (pattern E) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <x-reveal>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.trading_conditions.table_eyebrow') }}
                </p>
                <form method="GET" class="flex justify-end">
                    <x-select-filter
                        name="categorie"
                        onchange="this.form.submit()"
                        :options="$categories"
                        :selected="$selected"
                        :placeholder="__('app.trading_conditions.filter_all')"
                    />
                </form>
            </div>
        </x-reveal>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-reveal>
            <x-data-table :headers="[
                __('app.trading_conditions.table_instrument'),
                __('app.trading_conditions.table_category'),
                __('app.trading_conditions.table_spread'),
                __('app.trading_conditions.table_leverage'),
            ]">
                @forelse($instruments as $instrument)
                    <tr>
                        <td class="px-4 py-3 font-medium text-texte-principal">{{ $instrument->nom }} <span class="text-texte-secondaire">({{ $instrument->symbole_interne }})</span></td>
                        <td class="px-4 py-3 text-texte-secondaire">{{ $categories[$instrument->categorie] ?? $instrument->categorie }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $instrument->spread }}</td>
                        <td class="px-4 py-3 tabular-nums">1:{{ $instrument->levier_max }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-texte-secondaire">{{ __('app.common.no_results') }}</td></tr>
                @endforelse
                <x-slot:pagination>{{ $instruments->links() }}</x-slot:pagination>
            </x-data-table>
        </x-reveal>
    </section>

</x-layouts.public>
