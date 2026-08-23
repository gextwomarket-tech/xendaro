@php
    $tiers = config('affiliate.tiers', []);
@endphp
<x-layouts.public :title="__('app.affiliate.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.affiliate.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.affiliate.subtitle') }}</p>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="rounded-sm bg-fond-card border border-bordure-subtile p-8">
            <p class="text-texte-secondaire leading-relaxed">{{ __('app.affiliate.intro') }}</p>
        </div>
    </section>

    {{-- Bareme de commissions --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <h2 class="font-display text-2xl font-semibold text-texte-principal mb-6 text-center">{{ __('app.affiliate.tiers_title') }}</h2>
        <x-card-grid cols="3">
            @forelse($tiers as $tier)
                <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6 text-center">
                    <p class="text-texte-secondaire text-xs uppercase tracking-wide">{{ $tier['label'] }}</p>
                    <p class="mt-2 text-3xl font-display font-bold text-couleur-principale tabular-nums">{{ $tier['commission'] }}</p>
                    <p class="mt-2 text-sm text-texte-secondaire">{{ $tier['range'] }}</p>
                </div>
            @empty
                <x-stat-card :label="__('app.affiliate.tier_1_label')" value="20%" />
                <x-stat-card :label="__('app.affiliate.tier_2_label')" value="30%" />
                <x-stat-card :label="__('app.affiliate.tier_3_label')" value="40%" />
            @endforelse
        </x-card-grid>
    </section>

    <x-floating-button href="{{ url('/inscription') }}" aria-label="{{ __('app.affiliate.cta') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg>
    </x-floating-button>

</x-layouts.public>
