@props(['siteIdentifier' => null])
<footer class="border-t border-bordure-subtile bg-fond-surface mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-sm">
        <div class="space-y-3">
            <x-logo size="sm" />
            <p class="text-texte-secondaire">{{ $siteIdentifier->slogan ?? '' }}</p>
        </div>
        <div class="space-y-2">
            <p class="text-texte-principal font-semibold mb-1">{{ __('app.nav.our_services') }}</p>
            <a href="{{ url('/nos-services/types-de-comptes') }}" class="block text-texte-secondaire hover:text-texte-principal">Types de comptes</a>
            <a href="{{ url('/nos-services/plateformes') }}" class="block text-texte-secondaire hover:text-texte-principal">Plateformes</a>
            <a href="{{ url('/marches') }}" class="block text-texte-secondaire hover:text-texte-principal">Marchés</a>
        </div>
        <div class="space-y-2">
            <p class="text-texte-principal font-semibold mb-1">Légal</p>
            <a href="{{ url('/cgv') }}" class="block text-texte-secondaire hover:text-texte-principal">CGV</a>
            <a href="{{ url('/confidentialite') }}" class="block text-texte-secondaire hover:text-texte-principal">Confidentialité</a>
            <a href="{{ url('/cookies') }}" class="block text-texte-secondaire hover:text-texte-principal">Cookies</a>
            <a href="{{ url('/avertissement-risques') }}" class="block text-texte-secondaire hover:text-texte-principal">Risques</a>
        </div>
        <div class="space-y-2">
            <p class="text-texte-principal font-semibold mb-1">{{ __('app.nav.contact') }}</p>
            @if($siteIdentifier?->email_pro_1)
                <p class="text-texte-secondaire">{{ $siteIdentifier->email_pro_1 }}</p>
            @endif
            @if($siteIdentifier?->phone_contact_1)
                <p class="text-texte-secondaire">{{ $siteIdentifier->phone_contact_1 }}</p>
            @endif
        </div>
    </div>
    <div class="border-t border-bordure-subtile py-4 text-center text-xs text-texte-secondaire">
        &copy; {{ date('Y') }} {{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}. Tous droits réservés.
    </div>
</footer>
