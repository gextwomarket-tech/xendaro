<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error(string $message = 'Error', int $code = 400, mixed $errors = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];
        if ($errors !== null) {
            $payload['errors'] = $errors;
        }
        return response()->json($payload, $code);
    }

    public static function paginated(mixed $data, string $message = 'Success'): JsonResponse
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message,
        ], $data->toArray()));
    }
}
