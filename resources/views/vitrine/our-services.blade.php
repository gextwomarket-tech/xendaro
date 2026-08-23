<x-layouts.public :title="__('app.services.title')">

    {{-- Hero --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.services.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.services.subtitle') }}</p>
    </section>

    {{-- Cards vers sous-pages --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-card-grid cols="3">
            <x-card-item href="{{ url('/nos-services/types-de-comptes') }}" :title="__('app.services.account_types_title')" :description="__('app.services.account_types_desc')" />
            <x-card-item href="{{ url('/nos-services/plateformes') }}" :title="__('app.services.platforms_title')" :description="__('app.services.platforms_desc')" />
            <x-card-item href="{{ url('/nos-services/conditions-de-trading') }}" :title="__('app.services.trading_conditions_title')" :description="__('app.services.trading_conditions_desc')" />
        </x-card-grid>
    </section>

    {{-- Contenu nos_services (site_identifier) --}}
    @if($siteIdentifier?->nos_services)
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="rounded-sm bg-fond-card border border-bordure-subtile p-8 prose prose-invert max-w-none text-texte-secondaire">
                @if(strip_tags($siteIdentifier->nos_services) === $siteIdentifier->nos_services)
                    <p>{!! nl2br(e($siteIdentifier->nos_services)) !!}</p>
                @else
                    {!! $siteIdentifier->nos_services !!}
                @endif
            </div>
        </section>
    @endif

    <x-floating-button href="{{ url('/contact') }}" aria-label="{{ __('app.floating.support') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
