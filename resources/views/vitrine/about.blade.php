<x-layouts.public :title="__('app.about.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.about.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.about.subtitle') }}</p>
    </section>

    {{-- Contenu about_us (site_identifier) --}}
    @if($siteIdentifier?->about_us)
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="rounded-sm bg-fond-card border border-bordure-subtile p-8 prose prose-invert max-w-none text-texte-secondaire leading-relaxed">
                @if(strip_tags($siteIdentifier->about_us) === $siteIdentifier->about_us)
                    <p>{!! nl2br(e($siteIdentifier->about_us)) !!}</p>
                @else
                    {!! $siteIdentifier->about_us !!}
                @endif
            </div>
        </section>
    @endif

    {{-- Chiffres cles --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-card-grid cols="4">
            <x-stat-card label="Traders actifs" value="12 400+" />
            <x-stat-card label="Instruments disponibles" value="{{ \App\Models\MarketInstrument::where('est_actif', true)->count() }}" />
            <x-stat-card label="Volume mensuel" value="$85M+" />
            <x-stat-card label="Support" value="24/7" />
        </x-card-grid>
    </section>

    {{-- Valeurs --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <h2 class="font-display text-2xl font-semibold text-texte-principal mb-6">{{ __('app.about.values_title') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <x-icon-feature :title="__('app.about.value_1_title')" :description="__('app.about.value_1_desc')" />
            <x-icon-feature :title="__('app.about.value_2_title')" :description="__('app.about.value_2_desc')" />
            <x-icon-feature :title="__('app.about.value_3_title')" :description="__('app.about.value_3_desc')" />
            <x-icon-feature :title="__('app.about.value_4_title')" :description="__('app.about.value_4_desc')" />
        </div>
    </section>

    <x-floating-button href="{{ url('/contact') }}" aria-label="{{ __('app.floating.support') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
