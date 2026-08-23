<?php

namespace App\Livewire\Client;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Page id 40 "support-tickets" - liste paginee + bouton Nouveau ticket (popup).
 */
#[Layout('components.layouts.dashboard')]
class SupportTickets extends Component
{
    use WithPagination;

    protected $listeners = ['ticket-created' => '$refresh'];

    public function render()
    {
        return view('livewire.client.support-tickets', [
            'tickets' => Ticket::where('user_id', Auth::id())->latest()->paginate(15),
        ]);
    }
}
