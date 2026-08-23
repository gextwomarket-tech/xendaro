@php use App\Services\MarketPriceService; @endphp
<div class="space-y-6">
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.markets.title') }}</h1>

    <div class="flex flex-wrap items-center gap-3">
        <x-search-input wire:model.live.debounce.400ms="search" class="w-full sm:w-64" />
        <x-select-filter wire:model.live="categorie" :options="$categories->mapWithKeys(fn($c) => [$c => ucfirst($c)])->toArray()" :placeholder="__('app.common.all')" />
    </div>

    <x-data-table :headers="[__('app.client.markets.instrument'), __('app.client.markets.price'), __('app.client.markets.variation'), __('app.client.markets.spread'), '']">
        @forelse($instruments as $instrument)
            @php
                $price = MarketPriceService::currentPrice($instrument);
                $ref = (float) $instrument->prix_reference;
                $variation = $ref > 0 ? (($price - $ref) / $ref) * 100 : 0;
            @endphp
            <tr>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ $instrument->nom }}</span>
                        <span class="text-[10px] uppercase text-texte-secondaire">{{ $instrument->categorie }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 tabular-nums">{{ number_format($price, 5) }}</td>
                <td class="px-4 py-3 tabular-nums {{ $variation >= 0 ? 'text-succes' : 'text-danger' }}">
                    {{ $variation >= 0 ? '+' : '' }}{{ number_format($variation, 2) }}%
                </td>
                <td class="px-4 py-3 tabular-nums">{{ number_format($instrument->spread, 5) }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ url('/trade').'?symbole='.$instrument->symbole_interne }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal text-xs font-semibold px-3 py-1.5 hover:brightness-110 transition">
                        {{ __('app.client.markets.trade_button') }}
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.common.no_results') }}</td></tr>
        @endforelse

        <x-slot:pagination>{{ $instruments->links() }}</x-slot:pagination>
    </x-data-table>
</div>
