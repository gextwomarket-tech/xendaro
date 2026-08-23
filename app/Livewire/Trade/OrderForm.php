<?php

namespace App\Livewire\Trade;

use App\Models\MarketInstrument;
use App\Services\MarketPriceService;
use App\Services\TradingService;
use Livewire\Attributes\On;
use Livewire\Component;
use RuntimeException;

/**
 * Formulaire de prise de trade (Volume/SL/TP/type d'ordre + boutons Buy/Sell), reutilise a
 * l'identique (meme classe, deux instances) sous le graph (variant='main'/'main-mobile') et a
 * l'interieur du dialog de trade rapide sans overlay (variant='quick', voir QuickTradeModal) -
 * conformement a l'instruction "Ne pas dupliquer le formulaire: passer le symbole selectionne
 * en parametre au composant OrderForm partage" (xendaro-fox-plan.json > Page id 37).
 *
 * Contient egalement le toggle demo/reel de la fonctionnalite "toggle_switch (formulaire)",
 * synchronise avec celui de la sidebar droite (App\Livewire\Trade\ProfileCard) via l'evenement
 * Livewire global 'mode-changed'.
 */
class OrderForm extends Component
{
    public ?int $instrumentId = null;

    public string $modeActif = 'demo';

    public bool $modeReel = false;

    /** 'main' (sous le graph, desktop), 'main-mobile' (onglet Graph mobile) ou 'quick' (dialog sans overlay). */
    public string $variant = 'main';

    public float $volume = 0.01;

    public ?float $stopLoss = null;

    public ?float $takeProfit = null;

    public string $typeOrdre = 'marche';

    public function mount(?int $instrumentId = null, string $modeActif = 'demo', string $variant = 'main'): void
    {
        $this->instrumentId = $instrumentId;
        $this->modeActif = $modeActif;
        $this->modeReel = $modeActif === 'reel';
        $this->variant = $variant;
    }

    #[On('symbol-selected')]
    public function onSymbolSelected(int $instrumentId): void
    {
        $this->instrumentId = $instrumentId;
    }

    #[On('mode-changed')]
    public function onModeChanged(string $mode): void
    {
        $this->modeActif = $mode;
        $this->modeReel = $mode === 'reel';
    }

    public function updatedModeReel(bool $value): void
    {
        $this->modeActif = $value ? 'reel' : 'demo';
        $this->dispatch('mode-changed', mode: $this->modeActif);
    }

    public function updatedVolume($value): void
    {
        $this->volume = max(0.01, round((float) $value, 2));
    }

    public function getInstrumentProperty(): ?MarketInstrument
    {
        return $this->instrumentId ? MarketInstrument::find($this->instrumentId) : null;
    }

    public function getPrixActuelProperty(): float
    {
        return $this->instrument ? MarketPriceService::currentPrice($this->instrument) : 0.0;
    }

    public function getMargeRequiseProperty(): float
    {
        if (! $this->instrument || $this->volume <= 0) {
            return 0.0;
        }

        return TradingService::calculerMarge($this->instrument, (float) $this->volume, $this->prixActuel);
    }

    public function placerOrdre(string $sens): void
    {
        $this->validate([
            'volume' => 'required|numeric|min:0.01',
            'stopLoss' => 'nullable|numeric|min:0',
            'takeProfit' => 'nullable|numeric|min:0',
        ]);

        if (! $this->instrument) {
            $this->dispatch('toast', type: 'error', message: __('app.trade.errors.no_instrument_selected'));

            return;
        }

        try {
            TradingService::openPosition(
                auth()->user(),
                $this->instrument,
                $this->modeActif,
                $sens,
                (float) $this->volume,
                $this->stopLoss !== null && $this->stopLoss !== '' ? (float) $this->stopLoss : null,
                $this->takeProfit !== null && $this->takeProfit !== '' ? (float) $this->takeProfit : null,
                $this->typeOrdre,
            );

            $this->stopLoss = null;
            $this->takeProfit = null;

            $this->dispatch('toast', type: 'success', message: __('app.trade.trade_opened'));
            $this->dispatch('trade-opened');

            if ($this->variant === 'quick') {
                $this->dispatch('close-modal', name: 'quick-trade');
            }
        } catch (RuntimeException $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.trade.order-form');
    }
}
