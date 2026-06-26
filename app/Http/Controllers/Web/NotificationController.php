<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()->paginate(15);
        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function recent(Request $request)
    {
        $page    = max(1, (int) $request->input('page', 1));
        $perPage = 8;
        $query   = $request->user()->notifications()->latest();
        $total   = $query->count();

        $items = $query->skip(($page - 1) * $perPage)->take($perPage)->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'title'   => $n->data['title']   ?? $n->data['subject'] ?? class_basename($n->type),
                'message' => $n->data['message'] ?? $n->data['body']    ?? $n->data['content'] ?? '',
                'read'    => ! is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
                'url'     => $n->data['url'] ?? null,
            ]);

        return response()->json([
            'notifications' => $items,
            'unread_count'  => $request->user()->unreadNotifications()->count(),
            'total'         => $total,
            'page'          => $page,
            'per_page'      => $perPage,
            'total_pages'   => (int) ceil($total / $perPage) ?: 1,
        ]);
    }

    public function unreadCount(Request $request)
    {
        return response()->json([
            'count' => $request->user()->notifications()->whereNull('read_at')->count()
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->notifications()->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }

    public function markAsRead(Request $request, $id)
    {
        $request->user()->notifications()->find($id)?->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->notifications()->find($id)?->delete();
        return back()->with('success', 'Notification supprimée.');
    }

    public function preferences(Request $request)
    {
        return view('notifications.preferences', ['user' => $request->user()]);
    }

    public function updatePreferences(Request $request)
    {
        $request->user()->update($request->validate([
            'notify_trades' => 'boolean',
            'notify_deposits' => 'boolean',
            'notify_withdrawals' => 'boolean',
            'notify_system' => 'boolean',
        ]));

        return back()->with('success', 'Préférences mises à jour.');
    }
}
