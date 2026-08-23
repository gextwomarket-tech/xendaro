<div class="h-full min-h-0">
    {{--
        Desktop (>= md): grille 3 colonnes fixe (xendaro-fox-plan.json > Page id 37 >
        layout_plein_ecran: "grid-template-columns: 280px 1fr 280px; sur desktop").
    --}}
    <div class="hidden md:grid h-full min-h-0" style="grid-template-columns: 280px 1fr 280px;">
        {{-- Colonne gauche: watchlist (haut) + historique de trade (bas) --}}
        <div class="flex flex-col min-h-0 border-r border-bordure-subtile bg-fond-surface/40">
            <div class="flex-1 min-h-0 overflow-hidden flex flex-col">
                <livewire:trade.watchlist :active-instrument-id="$activeInstrumentId" :mode-actif="$modeActif" wire:key="watchlist-desktop" />
            </div>
            <div class="h-72 shrink-0 border-t border-bordure-subtile overflow-hidden flex flex-col">
                <livewire:trade.trade-history-panel :mode-actif="$modeActif" wire:key="history-desktop" />
            </div>
        </div>

        {{-- Colonne centrale: ticker + graph + formulaire d'ordre --}}
        <div class="flex flex-col min-h-0">
            <livewire:trade.price-ticker :instrument-id="$activeInstrumentId" wire:key="ticker-desktop" />
            <div class="flex-1 min-h-0">
                <livewire:trade.chart-panel :instrument-id="$activeInstrumentId" wire:key="chart-desktop" />
            </div>
            <div class="shrink-0 border-t border-bordure-subtile">
                <livewire:trade.order-form :instrument-id="$activeInstrumentId" :mode-actif="$modeActif" variant="main" wire:key="order-form-main" />
            </div>
        </div>

        {{-- Colonne droite: profil + resume de compte (haut) + positions ouvertes (bas) --}}
        <div class="flex flex-col min-h-0 border-l border-bordure-subtile bg-fond-surface/40">
            <livewire:trade.profile-card :mode-actif="$modeActif" wire:key="profile-desktop" />
            <livewire:trade.account-summary-widget :mode-actif="$modeActif" wire:key="summary-desktop" />
            <div class="flex-1 min-h-0 overflow-hidden border-t border-bordure-subtile flex flex-col">
                <livewire:trade.open-positions :mode-actif="$modeActif" wire:key="positions-desktop" />
            </div>
        </div>
    </div>

    {{--
        Mobile (< md): 3 onglets Graph / Watchlist / Positions (xendaro-fox-plan.json > Page id 37
        > layout_plein_ecran: "En mobile: passage en onglets"). Reutilise <x-tabs>.
    --}}
    <div class="md:hidden h-full min-h-0 overflow-y-auto">
        <x-tabs :tabs="[
            'graph' => __('app.trade.tabs.graph'),
            'watchlist' => __('app.trade.tabs.watchlist'),
            'positions' => __('app.trade.tabs.positions'),
        ]" class="h-full min-h-0 flex flex-col [&>div:first-child]:mb-0 [&>div:last-child]:flex-1 [&>div:last-child]:min-h-0 [&>div:last-child]:overflow-y-auto">
            <div x-show="activeTab === 'graph'" x-cloak>
                <livewire:trade.price-ticker :instrument-id="$activeInstrumentId" wire:key="ticker-mobile" />
                <div class="h-80"><livewire:trade.chart-panel :instrument-id="$activeInstrumentId" wire:key="chart-mobile" /></div>
                <livewire:trade.order-form :instrument-id="$activeInstrumentId" :mode-actif="$modeActif" variant="main-mobile" wire:key="order-form-mobile" />
            </div>

            <div x-show="activeTab === 'watchlist'" x-cloak>
                <livewire:trade.watchlist :active-instrument-id="$activeInstrumentId" :mode-actif="$modeActif" wire:key="watchlist-mobile" />
                <livewire:trade.trade-history-panel :mode-actif="$modeActif" wire:key="history-mobile" />
            </div>

            <div x-show="activeTab === 'positions'" x-cloak>
                <livewire:trade.profile-card :mode-actif="$modeActif" wire:key="profile-mobile" />
                <livewire:trade.account-summary-widget :mode-actif="$modeActif" wire:key="summary-mobile" />
                <livewire:trade.open-positions :mode-actif="$modeActif" wire:key="positions-mobile" />
            </div>
        </x-tabs>
    </div>

    <livewire:trade.quick-trade-modal :instrument-id="$activeInstrumentId" :mode-actif="$modeActif" wire:key="quick-trade-modal" />
</div>
