<x-layouts.public :title="__('app.legal.cookies_title')">

    {{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
    <x-page-hero image="/images/trading/trading-02.jpg" :eyebrow="__('app.legal.hero_eyebrow')" size="sm">
        <h1 class="font-display text-2xl sm:text-4xl font-bold text-texte-principal">{{ __('app.legal.cookies_title') }}</h1>
        <p class="mt-4 text-base sm:text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.legal.cookies_intro') }}</p>
    </x-page-hero>

    <x-reveal>
        <x-legal-page :title="__('app.legal.cookies_title')" :content="$siteIdentifier?->cookies" />
    </x-reveal>

</x-layouts.public>
