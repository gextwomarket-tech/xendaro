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
@endphp
<x-layouts.public :title="__('app.tools.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.tools.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.tools.subtitle') }}</p>
    </section>

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

</x-layouts.public>
