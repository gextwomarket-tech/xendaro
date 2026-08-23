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

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.trading_conditions.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.trading_conditions.subtitle') }}</p>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <form method="GET" class="flex justify-end">
            <x-select-filter
                name="categorie"
                onchange="this.form.submit()"
                :options="$categories"
                :selected="$selected"
                :placeholder="__('app.trading_conditions.filter_all')"
            />
        </form>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
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
    </section>

</x-layouts.public>
