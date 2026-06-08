<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\KycLimitHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['kycDocuments', 'wallet', 'notificationPreferences']);
        return ApiResponse::success($user);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'preferred_currency' => 'nullable|string|max:10|in:USD,EUR,GBP',
        ]);

        $user = $request->user();

        if (isset($data['first_name']) || isset($data['last_name'])) {
            $data['name'] = ($data['first_name'] ?? $user->first_name) . ' ' . ($data['last_name'] ?? $user->last_name);
        }

        $user->update($data);

        return ApiResponse::success($user, 'Profil mis à jour');
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars/' . $user->id, 'public');
        $user->update(['avatar' => $path]);

        return ApiResponse::success(['avatar_url' => asset('storage/' . $path)], 'Avatar mis à jour');
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|string|in:SUPPRIMER',
        ]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Mot de passe incorrect', 422);
        }

        $user->tokens()->delete();
        $user->update(['status' => 'deleted', 'email' => 'deleted_' . $user->id . '_' . $user->email]);

        return ApiResponse::success(null, 'Compte supprimé');
    }

    public function kycStatus(Request $request): JsonResponse
    {
        return ApiResponse::success(KycLimitHelper::kycProgress($request->user()));
    }
}
