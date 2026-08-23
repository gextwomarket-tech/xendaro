<x-layouts.public :title="__('app.platforms.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.platforms.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.platforms.subtitle') }}</p>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-card-grid cols="3">
            <x-card-item :title="__('app.platforms.webtrader_title')" :description="__('app.platforms.webtrader_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </x-slot:icon>
            </x-card-item>
            <x-card-item :title="__('app.platforms.mobile_title')" :description="__('app.platforms.mobile_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                </x-slot:icon>
            </x-card-item>
            <x-card-item :title="__('app.platforms.desktop_title')" :description="__('app.platforms.desktop_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </x-slot:icon>
            </x-card-item>
        </x-card-grid>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 text-center">
        <a href="{{ url('/trade') }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
            {{ __('app.platforms.webtrader_cta') }}
        </a>
    </section>

</x-layouts.public>
