<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;

class ResponseErrorHelper
{
    public static function jsonResponse(
        bool $success = false,
        int $httpCode = 500,
        ?string $internalCode = null,
        string $message = 'An error has ocurred.'
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'httpCode' => $httpCode,
            'internalCode' => $internalCode,
            'message' => $message,
        ], $httpCode);
    }
}