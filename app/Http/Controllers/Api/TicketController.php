<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tickets = $request->user()->tickets()
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return ApiResponse::paginated($tickets);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|in:technical,financial,kyc,other',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'description' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $attachmentUrl = null;
        if ($request->hasFile('attachment')) {
            $attachmentUrl = $request->file('attachment')->store('tickets/' . $request->user()->id, 'public');
        }

        $ticket = $request->user()->tickets()->create([
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $data['priority'],
            'description' => $data['description'],
            'status' => 'open',
        ]);

        $ticket->replies()->create([
            'message' => $data['description'],
            'attachment_url' => $attachmentUrl,
            'is_admin_reply' => false,
        ]);

        return ApiResponse::success($ticket->load('replies'), 'Ticket créé', 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $ticket = $request->user()->tickets()->with('replies.user')->find($id);

        if (! $ticket) {
            return ApiResponse::error('Ticket introuvable', 404);
        }

        return ApiResponse::success($ticket);
    }

    public function reply(Request $request, int $id): JsonResponse
    {
        $ticket = $request->user()->tickets()->find($id);

        if (! $ticket) {
            return ApiResponse::error('Ticket introuvable', 404);
        }

        if ($ticket->status === 'closed') {
            return ApiResponse::error('Ticket fermé', 422);
        }

        $data = $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $attachmentUrl = null;
        if ($request->hasFile('attachment')) {
            $attachmentUrl = $request->file('attachment')->store('ticket_replies/' . $request->user()->id, 'public');
        }

        $reply = $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $data['message'],
            'attachment_url' => $attachmentUrl,
            'is_admin_reply' => false,
        ]);

        $ticket->update(['last_replied_at' => now()]);

        return ApiResponse::success($reply, 'Réponse ajoutée', 201);
    }

    public function close(Request $request, int $id): JsonResponse
    {
        $ticket = $request->user()->tickets()->find($id);

        if (! $ticket) {
            return ApiResponse::error('Ticket introuvable', 404);
        }

        $ticket->update(['status' => 'closed']);

        return ApiResponse::success($ticket, 'Ticket fermé');
    }

    public function reopen(Request $request, int $id): JsonResponse
    {
        $ticket = $request->user()->tickets()->find($id);

        if (! $ticket) {
            return ApiResponse::error('Ticket introuvable', 404);
        }

        $ticket->update(['status' => 'reopened']);

        return ApiResponse::success($ticket, 'Ticket rouvert');
    }
}
