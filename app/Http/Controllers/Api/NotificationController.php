<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->notifications();

        if ($request->filled('type')) {
            $query->where('data->type', $request->type);
        }

        if ($request->boolean('unread')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate($request->integer('per_page', 15));

        return ApiResponse::paginated($notifications);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();
        return ApiResponse::success(['count' => $count]);
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if (! $notification) {
            return ApiResponse::error('Notification introuvable', 404);
        }

        $notification->markAsRead();

        return ApiResponse::success(null, 'Notification marquée comme lue');
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return ApiResponse::success(null, 'Toutes les notifications marquées comme lues');
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if (! $notification) {
            return ApiResponse::error('Notification introuvable', 404);
        }

        $notification->delete();

        return ApiResponse::success(null, 'Notification supprimée');
    }

    public function preferences(Request $request): JsonResponse
    {
        $prefs = $request->user()->notificationPreferences()->get();
        return ApiResponse::success($prefs);
    }

    public function updatePreferences(Request $request): JsonResponse
    {
        $data = $request->validate([
            'preferences' => 'required|array',
            'preferences.*.category' => 'required|string|in:trades,transactions,marketing,system',
            'preferences.*.email' => 'boolean',
            'preferences.*.push' => 'boolean',
            'preferences.*.in_app' => 'boolean',
        ]);

        foreach ($data['preferences'] as $pref) {
            NotificationPreference::updateOrCreate(
                ['user_id' => $request->user()->id, 'category' => $pref['category']],
                [
                    'email' => $pref['email'] ?? true,
                    'push' => $pref['push'] ?? true,
                    'in_app' => $pref['in_app'] ?? true,
                ]
            );
        }

        return ApiResponse::success(null, 'Préférences mises à jour');
    }
}
