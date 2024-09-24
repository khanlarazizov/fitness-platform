<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show()
    {
        if (!auth()->user()) {
            return ResponseHelper::error(message: 'Profile not found');
        }

        return ResponseHelper::success(
            message: 'Profile found successfully',
            data: UserResource::make(auth()->user())
        );
    }

    public function update(UserProfileRequest $request)
    {
        if (!auth()->user()) {
            return ResponseHelper::error(message: 'Profile not found');
        }

        $user = User::find(auth()->user()->id);
        $user->update($request->validated());

        return ResponseHelper::success(
            message: 'Profile updated successfully',
            data: UserResource::make($user)
        );
    }
}
