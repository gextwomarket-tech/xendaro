<x-layouts.public :title="__('app.why_us.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.why_us.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.why_us.subtitle') }}</p>
    </section>

    {{-- Arguments de confiance --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <x-icon-feature :title="__('app.why_us.security_title')" :description="__('app.why_us.security_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </x-slot:icon>
            </x-icon-feature>
            <x-icon-feature :title="__('app.why_us.execution_title')" :description="__('app.why_us.execution_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </x-slot:icon>
            </x-icon-feature>
            <x-icon-feature :title="__('app.why_us.support_title')" :description="__('app.why_us.support_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414 1.414A9 9 0 105.636 18.364l1.414-1.414M12 8v4l3 3" /></svg>
                </x-slot:icon>
            </x-icon-feature>
            <x-icon-feature :title="__('app.why_us.regulation_title')" :description="__('app.why_us.regulation_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </x-slot:icon>
            </x-icon-feature>
            <x-icon-feature :title="__('app.why_us.privacy_title')" :description="__('app.why_us.privacy_desc')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </x-slot:icon>
            </x-icon-feature>
        </div>
    </section>

    {{-- Texte securite/regulation/donnees --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="rounded-sm bg-fond-card border border-bordure-subtile p-8">
            <h2 class="font-display text-xl font-semibold text-texte-principal mb-3">{{ __('app.why_us.text_title') }}</h2>
            <p class="text-texte-secondaire leading-relaxed">{{ __('app.why_us.text_body') }}</p>
        </div>
    </section>

</x-layouts.public>
