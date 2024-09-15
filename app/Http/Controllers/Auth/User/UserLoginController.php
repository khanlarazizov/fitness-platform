<?php

namespace App\Http\Controllers\Auth\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    /**
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function store(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (!Auth::attempt($credentials, $remember)) {
            return ResponseHelper::error(
                message: 'Unauthorized',
                statusCode: 401
            );
        }
        $user = User::firstWhere('email', $request->email);
        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHelper::success(
            message: 'User logged successfully',
            data: [
                'token' => $token,
                'user' => new UserResource($user)
            ]);
    }

    /**
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function destroy(UserLoginRequest $request): JsonResponse
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
