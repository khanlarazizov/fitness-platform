<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TrainerLoginRequest;
use App\Models\Admin;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class TrainerLoginController extends Controller
{
    public function store(TrainerLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (!Auth::guard('trainer-api')->attempt($credentials, $remember)) {
            return ResponseHelper::error(
                message: 'Unauthorized',
                statusCode: 401
            );
        }
        $trainer = Trainer::firstWhere('email', $request->only('email'));
        $token = $trainer->createToken('auth_token')->plainTextToken;

        return ResponseHelper::success(
            message: 'Trainer logged successfully',
            data: [
                'token' => $token,
                'user' => [
                    'name' => $trainer->name,
                    'email' => $trainer->email
                ]
            ]);
    }

    public function destroy(TrainerLoginRequest $request)
    {
        $request->user()->tokens()->delete();
        return ResponseHelper::success(
            message: 'Trainer logged out successfully',
            statusCode: 204
        );
    }
}
