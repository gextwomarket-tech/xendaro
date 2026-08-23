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
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
@endphp
<x-layouts.public :title="__('app.calendar.title')">

    <x-page-hero image="/images/trading/trading-12.jpg" :eyebrow="__('app.calendar.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.calendar.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.calendar.subtitle') }}</p>
    </x-page-hero>

    {{-- Presentation : texte + image superposee (inversion gauche/droite vs autres pages) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <x-reveal direction="left" class="order-2 md:order-1">
                <h2 class="font-display text-2xl font-semibold text-texte-principal mb-3">{{ __('app.calendar.intro_title') }}</h2>
                <p class="text-texte-secondaire leading-relaxed">{{ __('app.calendar.intro_body') }}</p>
            </x-reveal>
            <x-reveal direction="right" :delay="100" class="order-1 md:order-2">
                <div class="relative">
                    <x-photo-card src="/images/trading/trading-11.jpg" :alt="__('app.calendar.title')" :rotate="1" />
                    <x-floating-badge position="bottom-left">
                        <p class="text-xs text-texte-secondaire uppercase tracking-wide">{{ __('app.calendar.table_importance') }}</p>
                        <p class="text-sm font-display font-semibold text-couleur-principale">{{ __('app.calendar.importance_haute') }}</p>
                    </x-floating-badge>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- Filtres --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <x-reveal>
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
        </x-reveal>
    </section>

    {{-- Tableau des evenements (donnees reelles) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-reveal direction="scale">
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
        </x-reveal>
    </section>

    {{-- Comprendre l'importance des evenements --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-8 text-center">{{ __('app.calendar.why_title') }}</h2>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-reveal :delay="0">
                <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-texte-secondaire/10 text-texte-secondaire mb-3">{{ __('app.calendar.importance_faible') }}</span>
                    <p class="font-semibold text-texte-principal">{{ __('app.calendar.why_low_title') }}</p>
                    <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.calendar.why_low_desc') }}</p>
                </div>
            </x-reveal>
            <x-reveal :delay="100">
                <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-avertissement/10 text-avertissement mb-3">{{ __('app.calendar.importance_moyenne') }}</span>
                    <p class="font-semibold text-texte-principal">{{ __('app.calendar.why_medium_title') }}</p>
                    <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.calendar.why_medium_desc') }}</p>
                </div>
            </x-reveal>
            <x-reveal :delay="200">
                <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-danger/10 text-danger mb-3">{{ __('app.calendar.importance_haute') }}</span>
                    <p class="font-semibold text-texte-principal">{{ __('app.calendar.why_high_title') }}</p>
                    <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.calendar.why_high_desc') }}</p>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- Banniere full-bleed CTA --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="/images/trading/trading-06.jpg" alt="" class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/70 via-fond-principal/90 to-fond-principal"></div>
        </div>
        <x-reveal direction="scale">
            <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.calendar.banner_title') }}</h2>
                <p class="mt-3 text-texte-secondaire">{{ __('app.calendar.banner_text') }}</p>
                <a href="{{ url('/trade') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.calendar.banner_cta') }}
                </a>
            </div>
        </x-reveal>
    </section>

</x-layouts.public>
