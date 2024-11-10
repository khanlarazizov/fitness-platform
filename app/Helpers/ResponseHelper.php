<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success($message = null, $data = [], $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message = null, $statusCode = 404): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], $statusCode);
    }
}
