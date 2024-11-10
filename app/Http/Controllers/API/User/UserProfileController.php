<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return ResponseHelper::error();
        }

        return ResponseHelper::success(data: UserResource::make($user));
    }

    public function update(UserProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return ResponseHelper::error();
        }

        $user->update($request->validated());

        return ResponseHelper::success(data: UserResource::make($user));
    }
}
