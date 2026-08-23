<?php

namespace App\Livewire\Client;

use App\Models\MarketInstrument;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Page id 35 "client-markets" - lecture seule des variations de prix, pas de prise de trade
 * (renvoie vers /trade). Reutilise MarketPriceService (deja cree pour markets id 7 / trade id 37).
 */
#[Layout('components.layouts.dashboard')]
class MarketsReadOnly extends Component
{
    use WithPagination;

    public string $search = '';

    public string $categorie = '';

    protected $queryString = ['search', 'categorie'];

    public function updating(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MarketInstrument::where('est_actif', true)
            ->when($this->search, fn ($q) => $q->where('nom', 'like', '%'.$this->search.'%'))
            ->when($this->categorie, fn ($q) => $q->where('categorie', $this->categorie))
            ->orderBy('nom');

        return view('livewire.client.markets-read-only', [
            'instruments' => $query->paginate(15),
            'categories' => MarketInstrument::distinct()->pluck('categorie'),
        ]);
    }
}
