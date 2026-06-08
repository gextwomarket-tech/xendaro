<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $user = $request->user();

        // Defense in depth: Verify KYC status (middleware already does this, but safety check)
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez compléter votre vérification KYC pour accéder au support.');
        }

        $tickets = Ticket::where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->paginate(15);
        return view('dashboard.support', compact('tickets'));
    }

    public function create(Request $request): \Illuminate\View\View
    {
        $tickets = Ticket::where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('dashboard.support', compact('tickets'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'subject'  => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'nullable|in:low,medium,high',
            'message'  => 'required|string|min:10',
        ]);

        $ticket = Ticket::create([
            'user_id'  => $request->user()->id,
            'subject'  => $validated['subject'],
            'category' => $validated['category'],
            'priority' => $validated['priority'] ?? 'medium',
            'description'  => $validated['message'],
            'status'   => 'open',
        ]);

        // Créer la première réponse (le message initial)
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $request->user()->id,
            'message'   => $validated['message'],
            'is_admin_reply' => false,
        ]);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket #' . $ticket->id . ' créé avec succès.');
    }

    public function show(Request $request, $id): \Illuminate\View\View
    {
        $ticket  = Ticket::where('user_id', $request->user()->id)->findOrFail($id);
        $replies = $ticket->replies()->orderBy('created_at')->get();
        $tickets = Ticket::where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->paginate(15);
        
        return view('dashboard.support', compact('ticket', 'tickets', 'replies'));
    }

    public function reply(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $ticket = Ticket::where('user_id', $request->user()->id)->findOrFail($id);
        
        $validated = $request->validate([
            'message' => 'required|string|min:5',
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $request->user()->id,
            'message'   => $validated['message'],
            'is_admin_reply' => false,
        ]);

        $ticket->update([
            'last_replied_at' => now(),
            'status' => 'open',
        ]);

        return back()->with('success', 'Réponse envoyée.');
    }

    public function close(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        Ticket::where('user_id', $request->user()->id)->findOrFail($id)
              ->update(['status' => 'closed']);
        return back()->with('success', 'Ticket fermé.');
    }

    public function reopen(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        Ticket::where('user_id', $request->user()->id)->findOrFail($id)
              ->update(['status' => 'open']);
        return back()->with('success', 'Ticket réouvert.');
    }
}
