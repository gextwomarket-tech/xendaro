<?php

namespace App\Livewire\Client;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Page id 41 "ticket-detail" > formulaire de reponse.
 * Ajoute un TicketMessage et repasse le ticket en 'ouvert' si le client relance
 * un ticket ferme/en_cours (permet de rouvrir la conversation cote support).
 */
class ReplyTicketForm extends Component
{
    public int $ticketId;

    public string $message = '';

    protected function rules(): array
    {
        return ['message' => ['required', 'string', 'min:2']];
    }

    public function submit(): void
    {
        $ticket = Ticket::where('id', $this->ticketId)->where('user_id', Auth::id())->firstOrFail();

        $this->validate();

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'auteur_id' => Auth::id(),
            'message' => $this->message,
            'est_admin' => false,
        ]);

        if ($ticket->statut === 'ferme') {
            $ticket->update(['statut' => 'ouvert']);
        }

        $this->reset('message');
        $this->dispatch('toast', type: 'success', message: __('app.client.support.message_sent'));
        $this->dispatch('ticket-message-sent');
    }

    public function render()
    {
        return view('livewire.client.reply-ticket-form');
    }
}
