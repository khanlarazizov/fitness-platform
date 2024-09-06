<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SentLinkRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\WelcomeNewUser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
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
//        Notification::send($user, new WelcomeNewUser($user));

        return ResponseHelper::success(
            message: 'User registered succesfully',
            data: new UserResource($user),
            statusCode: 201
        );
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = User::firstWhere('email', $request->email);
            $token = $user->createToken('auth_token')->plainTextToken;

            return ResponseHelper::success(
                message: 'User logged successfully',
                data: [
                    'token' => $token,
                    'user' => new UserResource($user)
                ]);
        }

        return ResponseHelper::error(
            message: 'Unauthorized',
            statusCode: 401
        );
    }

    public function logout(LoginUserRequest $request): JsonResponse
    {
        if ($request->user()->tokens()->delete()) {
            return response()->json();
        }

        return response()->json('asddasd');
    }

    public function sendResetLinkEmail(SentLinkRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 500);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            });

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 500);
    }

    public function profile()
    {
        $user = auth()->user();

        if ($user)
            return ResponseHelper::success(
                message: 'Profile found successfully',
                data: new UserResource($user),
            );
        return ResponseHelper::error(message: 'Profile  not found');
    }
}
