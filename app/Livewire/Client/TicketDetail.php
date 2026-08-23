<?php

namespace App\Livewire\Client;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Page id 41 "ticket-detail" - fil de discussion d'un ticket de support.
 */
#[Layout('components.layouts.dashboard')]
class TicketDetail extends Component
{
    public Ticket $ticket;

    protected $listeners = ['ticket-message-sent' => '$refresh'];

    public function mount(Ticket $ticket): void
    {
        abort_unless($ticket->user_id === Auth::id(), 403);

        $this->ticket = $ticket;
    }

    public function render()
    {
        return view('livewire.client.ticket-detail', [
            'messages' => $this->ticket->messages()->with('auteur')->get(),
        ]);
    }
}
