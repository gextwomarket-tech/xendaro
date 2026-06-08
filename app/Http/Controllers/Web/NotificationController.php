<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Defense in depth: Verify KYC status (middleware already does this, but safety check)
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez compléter votre vérification KYC pour accéder à vos notifications.');
        }

        $notifications = $user->notifications()->paginate(15);
        return view('notifications.index', ['notifications' => $notifications]);
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
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }

    public function markAsRead(Request $request, $id)
    {
        $request->user()->notifications()->find($id)?->update(['read_at' => now()]);
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
