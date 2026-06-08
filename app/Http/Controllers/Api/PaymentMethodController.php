<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $methods = $request->user()->paymentMethods()->latest()->get();
        return ApiResponse::success($methods);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => 'required|string|in:card,bank_transfer,crypto,mobile_money',
            'label' => 'nullable|string|max:255',
            'details' => 'required|array',
            'is_default' => 'boolean',
        ]);

        $user = $request->user();

        if (! empty($data['is_default'])) {
            $user->paymentMethods()->update(['is_default' => false]);
        }

        $method = $user->paymentMethods()->create($data);

        return ApiResponse::success($method, 'Méthode ajoutée', 201);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $method = $request->user()->paymentMethods()->find($id);

        if (! $method) {
            return ApiResponse::error('Méthode introuvable', 404);
        }

        $method->delete();

        return ApiResponse::success(null, 'Méthode supprimée');
    }
}
