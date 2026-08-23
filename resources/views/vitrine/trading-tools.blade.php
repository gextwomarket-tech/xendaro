@php
    // Taux de reference simules (MVP, alignes sur MarketInstrumentSeeder) pour le convertisseur de devises.
    // Bases en USD (1 unite de devise = X USD). Pas d'appel API externe pour le MVP.
    $ratesToUsd = [
        'USD' => 1,
        'EUR' => 1.0850,
        'GBP' => 1.2650,
        'JPY' => 1 / 149.50,
        'AUD' => 0.6550,
        'CHF' => 1 / 0.8800,
    ];
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
@endphp
<x-layouts.public :title="__('app.tools.title')">

    <x-page-hero image="/images/trading/trading-08.jpg" :eyebrow="__('app.tools.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.tools.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.tools.subtitle') }}</p>
    </x-page-hero>

    {{-- Presentation : image superposee + texte --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <x-reveal direction="left">
                <div class="relative">
                    <x-photo-card src="/images/trading/trading-16.jpg" alt="{{ __('app.tools.hero_title') }}" :rotate="-2" />
                    <x-floating-badge position="bottom-right">
                        <p class="text-xs text-texte-secondaire uppercase tracking-wide">{{ __('app.tools.tab_pip') }}</p>
                        <p class="text-lg font-display font-semibold text-couleur-principale">100% {{ __('app.tools.why_2_title') }}</p>
                    </x-floating-badge>
                </div>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <h2 class="font-display text-2xl font-semibold text-texte-principal mb-3">{{ __('app.tools.intro_title') }}</h2>
                <p class="text-texte-secondaire leading-relaxed">{{ __('app.tools.intro_body') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- Calculateurs (logique Alpine inchangee) --}}
    <x-reveal direction="scale">
    <section
        class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-20"
        x-data="{
            rates: @json($ratesToUsd),
            pip: { lot: 1, price: 1.08500, jpy: false },
            get pipValue() {
                const pipSize = this.pip.jpy ? 0.01 : 0.0001;
                const price = parseFloat(this.pip.price) || 0;
                if (price <= 0) return 0;
                return (pipSize / price) * (parseFloat(this.pip.lot) || 0) * 100000;
            },
            margin: { lot: 1, price: 1.08500, leverage: 500 },
            get marginRequired() {
                const leverage = parseFloat(this.margin.leverage) || 1;
                return ((parseFloat(this.margin.lot) || 0) * 100000 * (parseFloat(this.margin.price) || 0)) / leverage;
            },
            profit: { lot: 1, entry: 1.08500, exit: 1.09000, direction: 'buy' },
            get profitResult() {
                const diff = (parseFloat(this.profit.exit) || 0) - (parseFloat(this.profit.entry) || 0);
                const sign = this.profit.direction === 'buy' ? 1 : -1;
                return diff * sign * (parseFloat(this.profit.lot) || 0) * 100000;
            },
            convert: { amount: 100, from: 'EUR', to: 'USD' },
            get convertResult() {
                const amount = parseFloat(this.convert.amount) || 0;
                const fromRate = this.rates[this.convert.from] || 1;
                const toRate = this.rates[this.convert.to] || 1;
                return (amount * fromRate) / toRate;
            },
        }"
    >
        <x-tabs :tabs="[
            'pip' => __('app.tools.tab_pip'),
            'margin' => __('app.tools.tab_margin'),
            'profit' => __('app.tools.tab_profit'),
            'convert' => __('app.tools.tab_convert'),
        ]">
            {{-- Calculateur de pips --}}
            <div x-show="activeTab === 'pip'" x-cloak class="rounded-sm bg-fond-card border border-bordure-subtile p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.lot_size') }}</span>
                        <input type="number" step="0.01" min="0" x-model.number="pip.lot" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.price') }}</span>
                        <input type="number" step="0.00001" min="0" x-model.number="pip.price" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                </div>
                <label class="inline-flex items-center gap-2 text-sm text-texte-secondaire">
                    <input type="checkbox" x-model="pip.jpy" class="rounded border-bordure-subtile bg-fond-surface">
                    Paire JPY (pip = 0.01)
                </label>
                <div class="rounded-sm bg-fond-surface p-4 flex items-center justify-between">
                    <span class="text-sm text-texte-secondaire">{{ __('app.tools.pip_value_result') }}</span>
                    <span class="text-xl font-display font-semibold text-couleur-principale tabular-nums" x-text="'$' + pipValue.toFixed(2)"></span>
                </div>
            </div>

            {{-- Calculateur de marge --}}
            <div x-show="activeTab === 'margin'" x-cloak class="rounded-sm bg-fond-card border border-bordure-subtile p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.lot_size') }}</span>
                        <input type="number" step="0.01" min="0" x-model.number="margin.lot" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.price') }}</span>
                        <input type="number" step="0.00001" min="0" x-model.number="margin.price" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.leverage') }}</span>
                        <input type="number" step="1" min="1" x-model.number="margin.leverage" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                </div>
                <div class="rounded-sm bg-fond-surface p-4 flex items-center justify-between">
                    <span class="text-sm text-texte-secondaire">{{ __('app.tools.margin_result') }}</span>
                    <span class="text-xl font-display font-semibold text-couleur-principale tabular-nums" x-text="'$' + marginRequired.toFixed(2)"></span>
                </div>
            </div>

            {{-- Calculateur de profit --}}
            <div x-show="activeTab === 'profit'" x-cloak class="rounded-sm bg-fond-card border border-bordure-subtile p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.lot_size') }}</span>
                        <input type="number" step="0.01" min="0" x-model.number="profit.lot" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.direction') }}</span>
                        <select x-model="profit.direction" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                            <option value="buy">{{ __('app.tools.direction_buy') }}</option>
                            <option value="sell">{{ __('app.tools.direction_sell') }}</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.entry_price') }}</span>
                        <input type="number" step="0.00001" min="0" x-model.number="profit.entry" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.exit_price') }}</span>
                        <input type="number" step="0.00001" min="0" x-model.number="profit.exit" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                </div>
                <div class="rounded-sm bg-fond-surface p-4 flex items-center justify-between">
                    <span class="text-sm text-texte-secondaire">{{ __('app.tools.profit_result') }}</span>
                    <span class="text-xl font-display font-semibold tabular-nums" :class="profitResult >= 0 ? 'text-succes' : 'text-danger'" x-text="(profitResult >= 0 ? '+$' : '-$') + Math.abs(profitResult).toFixed(2)"></span>
                </div>
            </div>

            {{-- Convertisseur de devises --}}
            <div x-show="activeTab === 'convert'" x-cloak class="rounded-sm bg-fond-card border border-bordure-subtile p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.amount') }}</span>
                        <input type="number" step="0.01" min="0" x-model.number="convert.amount" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal tabular-nums focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.from_currency') }}</span>
                        <select x-model="convert.from" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                            <template x-for="code in Object.keys(rates)" :key="code"><option :value="code" x-text="code"></option></template>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm text-texte-secondaire">{{ __('app.tools.to_currency') }}</span>
                        <select x-model="convert.to" class="mt-1 w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
                            <template x-for="code in Object.keys(rates)" :key="code"><option :value="code" x-text="code"></option></template>
                        </select>
                    </label>
                </div>
                <div class="rounded-sm bg-fond-surface p-4 flex items-center justify-between">
                    <span class="text-sm text-texte-secondaire">{{ __('app.tools.convert_result') }}</span>
                    <span class="text-xl font-display font-semibold text-couleur-principale tabular-nums" x-text="convertResult.toFixed(2) + ' ' + convert.to"></span>
                </div>
            </div>
        </x-tabs>
    </section>
    </x-reveal>

    {{-- Pourquoi utiliser nos outils --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-8 text-center">{{ __('app.tools.why_title') }}</h2>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-reveal :delay="0">
                <x-icon-feature :title="__('app.tools.why_1_title')" :description="__('app.tools.why_1_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-9m-3 9V7a2 2 0 012-2h10a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="100">
                <x-icon-feature :title="__('app.tools.why_2_title')" :description="__('app.tools.why_2_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m9-8a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="200">
                <x-icon-feature :title="__('app.tools.why_3_title')" :description="__('app.tools.why_3_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
        </div>
    </section>

    {{-- Banniere full-bleed CTA --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="/images/trading/trading-14.jpg" alt="" class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/70 via-fond-principal/90 to-fond-principal"></div>
        </div>
        <x-reveal direction="scale">
            <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.tools.banner_title') }}</h2>
                <p class="mt-3 text-texte-secondaire">{{ __('app.tools.banner_text') }}</p>
                <a href="{{ url('/inscription') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.tools.banner_cta') }}
                </a>
            </div>
        </x-reveal>
    </section>

</x-layouts.public>
