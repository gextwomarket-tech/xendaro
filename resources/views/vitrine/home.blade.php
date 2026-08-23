<x-layouts.public>

    {{-- Hero --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 text-center">
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-texte-principal max-w-3xl mx-auto">
            {{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}
        </h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">
            {{ $siteIdentifier->slogan ?? '' }}
        </p>
        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="{{ url('/inscription') }}" class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                {{ __('app.nav.register') }}
            </a>
            <a href="{{ url('/nos-services') }}" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-principal font-semibold px-6 py-3 hover:border-couleur-principale/50 transition">
                {{ __('app.nav.our_services') }}
            </a>
        </div>
    </section>

    {{-- Stats --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-card-grid cols="4">
            <x-stat-card label="Traders actifs" value="12 400+" />
            <x-stat-card label="Instruments disponibles" value="{{ \App\Models\MarketInstrument::where('est_actif', true)->count() }}" />
            <x-stat-card label="Volume mensuel" value="$85M+" />
            <x-stat-card label="Support" value="24/7" />
        </x-card-grid>
    </section>

    {{-- Categories de marches --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <h2 class="font-display text-2xl font-semibold text-texte-principal mb-6">Nos marchés</h2>
        <x-card-grid cols="3">
            <x-card-item href="{{ url('/marches?categorie=forex') }}" title="Forex" description="Majors, minors et exotiques." />
            <x-card-item href="{{ url('/marches?categorie=crypto') }}" title="Crypto" description="Bitcoin, Ethereum et plus." />
            <x-card-item href="{{ url('/marches?categorie=metal') }}" title="Or & Métaux" description="Or, argent, métaux précieux." />
            <x-card-item href="{{ url('/marches?categorie=indice') }}" title="Indices" description="S&P 500, Nasdaq, et plus." />
            <x-card-item href="{{ url('/marches?categorie=commodite') }}" title="Matières premières" description="Pétrole et matières premières." />
            <x-card-item href="{{ url('/marches?categorie=action') }}" title="Actions" description="Grandes capitalisations mondiales." />
        </x-card-grid>
    </section>

    {{-- Avantages --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <h2 class="font-display text-2xl font-semibold text-texte-principal mb-6">Pourquoi Xendaro Fox</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <x-icon-feature title="Sécurité des fonds" description="Vos fonds sont protégés et séparés des comptes opérationnels." />
            <x-icon-feature title="Exécution rapide" description="Une infrastructure pensée pour une exécution fiable des ordres." />
            <x-icon-feature title="Support 24/7" description="Une équipe disponible à tout moment pour vous accompagner." />
            <x-icon-feature title="Régulation" description="Une plateforme opérant dans un cadre conforme et transparent." />
        </div>
    </section>

    <x-floating-button href="{{ url('/contact') }}" aria-label="Contact">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
