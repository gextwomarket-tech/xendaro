<?php

namespace Tests\Feature\Client;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Page id 41 "ticket-detail" - verifie l'affichage du fil de discussion et l'envoi
 * d'une reponse via ReplyTicketForm (voir app/Livewire/Client/ReplyTicketForm.php).
 */
class TicketDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_ticket_and_reply(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $ticket = Ticket::create(['user_id' => $user->id, 'sujet' => 'Probleme de retrait', 'statut' => 'ouvert']);
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'auteur_id' => $user->id,
            'message' => 'Mon retrait est bloque depuis 3 jours.',
            'est_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('client.ticket-detail', $ticket));
        $response->assertStatus(200);
        $response->assertSee('Probleme de retrait');
        $response->assertSee('Mon retrait est bloque depuis 3 jours.');

        Livewire::actingAs($user)
            ->test(\App\Livewire\Client\ReplyTicketForm::class, ['ticketId' => $ticket->id])
            ->set('message', 'Merci de verifier a nouveau.')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'message' => 'Merci de verifier a nouveau.',
        ]);
    }

    public function test_other_user_cannot_view_ticket(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $intruder = User::factory()->create(['email_verified_at' => now()]);

        $ticket = Ticket::create(['user_id' => $owner->id, 'sujet' => 'Sujet prive', 'statut' => 'ouvert']);

        $response = $this->actingAs($intruder)->get(route('client.ticket-detail', $ticket));
        $response->assertStatus(403);
    }
}
