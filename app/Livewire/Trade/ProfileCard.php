<?php

namespace App\Livewire\Trade;

use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Sidebar droite (rangee haute): mini-card profil (reutilise <x-user-mini-card>) + 2 soldes
 * (demo/reel) + toggle demo/reel (xendaro-fox-plan.json > Page id 37 > fonctionnalite
 * "sidebar droite - rangee haute: Profil"). Le toggle est synchronise avec celui du
 * OrderForm via l'evenement Livewire global 'mode-changed'.
 */
class ProfileCard extends Component
{
    public string $modeActif = 'demo';

    public bool $modeReel = false;

    public function mount(string $modeActif = 'demo'): void
    {
        $this->modeActif = $modeActif;
        $this->modeReel = $modeActif === 'reel';
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

    public function render()
    {
        return view('livewire.trade.profile-card', [
            'wallet' => auth()->user()->wallet,
        ]);
    }
}
