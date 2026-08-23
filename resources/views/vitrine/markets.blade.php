@php
    // Variation 24h simulee (MVP, voir xendaro-fox-plan.json > markets), deterministe par instrument + jour
    // pour rester stable pendant toute la journee (n'impacte pas MarketPriceService, dedie a la page Trade).
    $change24h = function (\App\Models\MarketInstrument $instrument): float {
        $hash = crc32($instrument->symbole_interne.'|'.now()->format('Y-m-d'));
        return round((($hash % 601) - 300) / 100, 2); // -3.00% a +3.00%
    };
@endphp
<x-layouts.public :title="__('app.markets.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.markets.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.markets.subtitle') }}</p>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
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
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-data-table :headers="[
            __('app.markets.table_instrument'),
            __('app.markets.table_price'),
            __('app.markets.table_change'),
            __('app.markets.table_spread'),
            '',
        ]">
            @forelse($instruments as $instrument)
                @php $variation = $change24h($instrument); @endphp
                <tr>
                    <td class="px-4 py-3">
                        <a href="{{ url('/marches/'.$instrument->symbole_interne) }}" class="font-medium text-texte-principal hover:text-couleur-principale transition">
                            {{ $instrument->nom }} <span class="text-texte-secondaire">({{ $instrument->symbole_interne }})</span>
                        </a>
                    </td>
                    <td class="px-4 py-3 tabular-nums">{{ number_format((float) $instrument->prix_reference, 5) }}</td>
                    <td class="px-4 py-3 tabular-nums {{ $variation >= 0 ? 'text-succes' : 'text-danger' }}">
                        {{ $variation >= 0 ? '+' : '' }}{{ $variation }}%
                    </td>
                    <td class="px-4 py-3 tabular-nums">{{ $instrument->spread }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ url('/marches/'.$instrument->symbole_interne) }}" class="text-couleur-principale hover:underline text-sm font-medium">{{ __('app.markets.view_detail') }}</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-texte-secondaire">{{ __('app.common.no_results') }}</td></tr>
            @endforelse
            <x-slot:pagination>{{ $instruments->links() }}</x-slot:pagination>
        </x-data-table>
    </section>

</x-layouts.public>
