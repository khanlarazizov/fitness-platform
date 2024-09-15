<?php

namespace App\Http\Controllers\Auth\User;

use App\Events\UserRegistered;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(RegisteredUserRequest $request)
    {
        $input = $request->validated();
        $input['password'] = Hash::make($request->input('password'));

        $user = User::create($input);
        $user->assignRole('user');

        if (!$user)
            return ResponseHelper::error(
                message: 'User not registered',
                statusCode: 400
            );

        event(new UserRegistered($user));

        return ResponseHelper::success(
            message: 'User registered succesfully',
            data: new UserResource($user),
            statusCode: 201
        );
    }
}
