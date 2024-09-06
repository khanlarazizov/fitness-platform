<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success($status = 'success', $message = null, $data = [], $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error($status = 'error', $message = null, $statusCode = 404): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $statusCode);
    }
}
