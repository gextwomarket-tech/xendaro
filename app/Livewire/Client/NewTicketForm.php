<?php

namespace App\Livewire\Client;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * Page id 40 "support-tickets" > popup_modal creation de ticket.
 * Cree le Ticket + le premier TicketMessage en une transaction DB.
 */
class NewTicketForm extends Component
{
    public string $sujet = '';

    public string $message = '';

    protected function rules(): array
    {
        return [
            'sujet' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:5'],
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $ticket = DB::transaction(function () use ($validated) {
            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'sujet' => $validated['sujet'],
                'statut' => 'ouvert',
            ]);

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'auteur_id' => Auth::id(),
                'message' => $validated['message'],
                'est_admin' => false,
            ]);

            return $ticket;
        });

        $this->reset(['sujet', 'message']);
        $this->dispatch('toast', type: 'success', message: __('app.client.support.ticket_created'));
        $this->dispatch('close-modal', name: 'new-ticket');
        $this->dispatch('ticket-created');
        $this->redirectRoute('client.ticket-detail', ['ticket' => $ticket->id], navigate: false);
    }

    public function render()
    {
        return view('livewire.client.new-ticket-form');
    }
}
