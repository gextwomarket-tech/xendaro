@php
    $currency = request()->query('devise');
    $importance = request()->query('importance');
    $currencies = \App\Models\EconomicEvent::query()->distinct()->orderBy('devise')->pluck('devise', 'devise');
    $importanceOptions = [
        'faible' => __('app.calendar.importance_faible'),
        'moyenne' => __('app.calendar.importance_moyenne'),
        'haute' => __('app.calendar.importance_haute'),
    ];
    $events = \App\Models\EconomicEvent::when($currency, fn ($q) => $q->where('devise', $currency))
        ->when($importance, fn ($q) => $q->where('importance', $importance))
        ->orderBy('date_heure')
        ->paginate(10)
        ->withQueryString();
    $importanceClass = [
        'haute' => 'bg-danger/10 text-danger',
        'moyenne' => 'bg-avertissement/10 text-avertissement',
        'faible' => 'bg-texte-secondaire/10 text-texte-secondaire',
    ];
@endphp
<x-layouts.public :title="__('app.calendar.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.calendar.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.calendar.subtitle') }}</p>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <x-select-filter
                name="devise"
                onchange="this.form.submit()"
                :options="$currencies"
                :selected="$currency"
                :placeholder="__('app.calendar.filter_currency')"
            />
            <x-select-filter
                name="importance"
                onchange="this.form.submit()"
                :options="$importanceOptions"
                :selected="$importance"
                :placeholder="__('app.calendar.filter_importance')"
            />
        </form>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-data-table :headers="[
            __('app.calendar.table_date'),
            __('app.calendar.table_event'),
            __('app.calendar.table_currency'),
            __('app.calendar.table_importance'),
            __('app.calendar.table_previous'),
            __('app.calendar.table_forecast'),
            __('app.calendar.table_actual'),
        ]">
            @forelse($events as $event)
                <tr>
                    <td class="px-4 py-3 whitespace-nowrap text-texte-secondaire">{{ $event->date_heure->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 font-medium text-texte-principal">{{ $event->titre }}</td>
                    <td class="px-4 py-3">{{ $event->devise }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $importanceClass[$event->importance] ?? '' }}">
                            {{ $importanceOptions[$event->importance] ?? $event->importance }}
                        </span>
                    </td>
                    <td class="px-4 py-3 tabular-nums text-texte-secondaire">{{ $event->valeur_precedente ?? '-' }}</td>
                    <td class="px-4 py-3 tabular-nums text-texte-secondaire">{{ $event->valeur_prevue ?? '-' }}</td>
                    <td class="px-4 py-3 tabular-nums text-texte-principal">{{ $event->valeur_reelle ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-6 text-center text-texte-secondaire">{{ __('app.common.no_results') }}</td></tr>
            @endforelse
            <x-slot:pagination>{{ $events->links() }}</x-slot:pagination>
        </x-data-table>
    </section>

</x-layouts.public>
