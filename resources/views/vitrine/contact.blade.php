<x-layouts.public :title="__('app.contact.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.contact.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.contact.subtitle') }}</p>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- Formulaire --}}
            <div class="lg:col-span-3 rounded-sm bg-fond-card border border-bordure-subtile p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-texte-principal mb-5">{{ __('app.contact.form_title') }}</h2>
                @livewire('vitrine.contact-form')
            </div>

            {{-- Coordonnees --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-sm bg-fond-card border border-bordure-subtile p-6 sm:p-8">
                    <h2 class="font-display text-xl font-semibold text-texte-principal mb-5">{{ __('app.contact.coordinates_title') }}</h2>
                    <div class="space-y-5">
                        @if($siteIdentifier?->phone_contact_1)
                            <x-icon-feature :title="__('app.contact.phone_label')" :description="$siteIdentifier->phone_contact_1">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                </x-slot:icon>
                            </x-icon-feature>
                        @endif
                        @if($siteIdentifier?->email_pro_1)
                            <x-icon-feature :title="__('app.contact.email_label')" :description="$siteIdentifier->email_pro_1">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </x-slot:icon>
                            </x-icon-feature>
                        @endif
                        @if($siteIdentifier?->location_adresse)
                            <x-icon-feature :title="__('app.contact.address_label')" :description="$siteIdentifier->location_adresse">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </x-slot:icon>
                            </x-icon-feature>
                        @endif
                    </div>
                </div>

                {{-- Carte statique (optionnel MVP) --}}
                @if($siteIdentifier?->location_adresse)
                    <div class="rounded-sm bg-fond-card border border-bordure-subtile overflow-hidden">
                        <iframe
                            class="w-full h-56 grayscale invert-[0.9]"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q={{ urlencode($siteIdentifier->location_adresse) }}&output=embed">
                        </iframe>
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-layouts.public>
