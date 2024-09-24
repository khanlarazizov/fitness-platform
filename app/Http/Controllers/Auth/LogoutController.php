<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function __invoke(UserLoginRequest $request): JsonResponse
    {
        if ($request->user()->tokens()->delete()) {
            return ResponseHelper::success(
                message: 'User logged out successfully',
                statusCode: 204
            );
        }

        return ResponseHelper::error(
            message: 'Unauthorized',
            statusCode: 401
        );
    }

}
