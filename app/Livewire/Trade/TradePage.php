<?php

namespace App\Livewire\Trade;

use App\Models\MarketInstrument;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Composant Livewire racine de la page Trade (xendaro-fox-plan.json > Page id 37 - coeur du
 * projet), monte en plein ecran via components/layouts/trade.blade.php.
 *
 * Ce composant est le "parent orchestrateur": il detient l'etat partage minimal entre les
 * enfants ($modeActif = compte demo/reel actif, $activeInstrumentId = instrument affiche dans
 * le graph/ticker/formulaire) et le persiste en session pour qu'il survive entre deux visites
 * de /trade. La propagation aux enfants se fait de deux manieres complementaires:
 *   - valeur initiale passee en prop a chaque <livewire:trade.xxx :mode-actif="$modeActif" ... />
 *   - mises a jour ulterieures diffusees via les evenements Livewire globaux 'mode-changed' et
 *     'symbol-selected' (voir chaque composant enfant), que TradePage ecoute egalement pour
 *     garder la session a jour.
 */
#[Layout('components.layouts.trade')]
class TradePage extends Component
{
    public string $modeActif = 'demo';

    public ?int $activeInstrumentId = null;

    public function mount(): void
    {
        $this->modeActif = session('trade.mode_actif', 'demo');

        $instrumentId = session('trade.instrument_id');

        if (! $instrumentId || ! MarketInstrument::where('id', $instrumentId)->where('est_actif', true)->exists()) {
            $instrumentId = MarketInstrument::where('est_actif', true)->orderBy('categorie')->orderBy('nom')->value('id');
        }

        $this->activeInstrumentId = $instrumentId;
    }

    #[On('mode-changed')]
    public function onModeChanged(string $mode): void
    {
        $this->modeActif = $mode;
        session(['trade.mode_actif' => $mode]);
    }

    #[On('symbol-selected')]
    public function onSymbolSelected(int $instrumentId): void
    {
        $this->activeInstrumentId = $instrumentId;
        session(['trade.instrument_id' => $instrumentId]);
    }

    public function render()
    {
        return view('livewire.trade.trade-page');
    }
}
