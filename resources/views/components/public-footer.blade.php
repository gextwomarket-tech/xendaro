@props(['siteIdentifier' => null])

{{-- Bandeau CTA (au dessus du footer, sur toutes les pages statiques) --}}
<section class="relative overflow-hidden mt-24 border-t border-bordure-subtile">
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-fond-surface via-fond-principal to-fond-principal"></div>
    <div class="absolute -bottom-32 right-0 w-96 h-96 rounded-full bg-couleur-principale/10 blur-3xl -z-10"></div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <x-reveal>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">
                {{ __('app.cta.title') }}
            </h2>
            <p class="mt-3 text-texte-secondaire max-w-xl mx-auto">
                {{ __('app.cta.subtitle') }}
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/inscription') }}" class="inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 hover:shadow-[0_0_24px_rgba(245,166,35,0.35)] transition">
                    {{ __('app.nav.register') }}
                </a>
                <a href="{{ url('/contact') }}" class="inline-flex items-center justify-center rounded-sm border border-bordure-subtile text-texte-principal font-semibold px-6 py-3 hover:border-couleur-principale/50 transition">
                    {{ __('app.nav.contact') }}
                </a>
            </div>
        </x-reveal>
    </div>
</section>

{{-- Footer pro 4 colonnes --}}
<footer class="border-t border-bordure-subtile bg-fond-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 text-sm">
        <div class="space-y-4">
            <x-logo size="sm" />
            <p class="text-texte-secondaire max-w-xs">{{ $siteIdentifier->slogan ?? '' }}</p>
            @if($siteIdentifier?->reseaux_sociaux)
                <div class="flex items-center gap-3 pt-1">
                    @foreach((array) $siteIdentifier->reseaux_sociaux as $label => $href)
                        <a href="{{ $href }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-fond-card border border-bordure-subtile flex items-center justify-center text-texte-secondaire hover:text-couleur-principale hover:border-couleur-principale/50 transition text-xs">
                            {{ strtoupper(substr($label, 0, 1)) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-3">
            <p class="text-texte-principal font-display font-semibold">{{ __('app.nav.our_services') }}</p>
            <nav class="flex flex-col gap-2">
                <a href="{{ url('/nos-services/types-de-comptes') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.footer.account_types') }}</a>
                <a href="{{ url('/nos-services/plateformes') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.footer.platforms') }}</a>
                <a href="{{ url('/marches') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.nav.markets') }}</a>
                <a href="{{ url('/academie') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.nav.academy') }}</a>
                <a href="{{ url('/parrainage') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.nav.affiliate_program') }}</a>
            </nav>
        </div>

        <div class="space-y-3">
            <p class="text-texte-principal font-display font-semibold">{{ __('app.footer.legal') }}</p>
            <nav class="flex flex-col gap-2">
                <a href="{{ url('/cgv') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.footer.cgv') }}</a>
                <a href="{{ url('/confidentialite') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.footer.privacy') }}</a>
                <a href="{{ url('/cookies') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.footer.cookies') }}</a>
                <a href="{{ url('/avertissement-risques') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.footer.risk') }}</a>
                <a href="{{ url('/politique-aml') }}" class="text-texte-secondaire hover:text-couleur-principale transition">{{ __('app.footer.aml') }}</a>
            </nav>
        </div>

        <div class="space-y-3">
            <p class="text-texte-principal font-display font-semibold">{{ __('app.nav.contact') }}</p>
            <div class="flex flex-col gap-2 text-texte-secondaire">
                @if($siteIdentifier?->email_pro_1)
                    <a href="mailto:{{ $siteIdentifier->email_pro_1 }}" class="hover:text-couleur-principale transition">{{ $siteIdentifier->email_pro_1 }}</a>
                @endif
                @if($siteIdentifier?->phone_contact_1)
                    <p>{{ $siteIdentifier->phone_contact_1 }}</p>
                @endif
                @if($siteIdentifier?->location_adresse)
                    <p>{{ $siteIdentifier->location_adresse }}</p>
                @endif
                <a href="{{ url('/faq') }}" class="hover:text-couleur-principale transition">{{ __('app.nav.faq') }}</a>
            </div>
        </div>
    </div>

    <div class="border-t border-bordure-subtile">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-texte-secondaire">
            <p>&copy; {{ date('Y') }} {{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}. {{ __('app.footer.rights') }}</p>
            <p class="text-center sm:text-right max-w-2xl">{{ __('app.footer.risk_reminder') }}</p>
        </div>
    </div>
</footer>
