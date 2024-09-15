<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function store(AdminLoginRequest $request)
    {
//        $credentials = $request->only('email', 'password');
//        $remember = $request->boolean('remember');

//        $admin = Admin::firstWhere('email', $request->input('email'));
//
//        if (!$admin || !Hash::check($request->input('password'), $admin->password)) {
//            return ResponseHelper::error(
//                message: 'Unauthorized',
//                statusCode: 401
//            );
//        }
//
//        $token = $admin->createToken('auth_token')->plainTextToken;
//
//        return ResponseHelper::success(
//            message: 'Admin logged successfully',
//            data: [
//                'token' => $token,
//                'user' => [
//                    'name' => $admin->name,
//                    'email' => $admin->email
//                ]
//            ]);


        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (!Auth::guard('admin-api')->attempt($credentials, $remember)) {
            return ResponseHelper::error(
                message: 'Unauthorized',
                statusCode: 401
            );
        }
        $admin = Admin::firstWhere('email', $request->email);
        $token = $admin->createToken('auth_token')->plainTextToken;

        return ResponseHelper::success(
            message: 'Admin logged successfully',
            data: [
                'token' => $token,
                'user' => [
                    'name' => $admin->name,
                    'email' => $admin->email
                ]
            ]);
    }

    public function destroy(AdminLoginRequest $request)
    {
        $request->user()->tokens()->delete();
        return ResponseHelper::success(
            message: 'Admin logged out successfully',
            statusCode: 204
        );
    }
}
