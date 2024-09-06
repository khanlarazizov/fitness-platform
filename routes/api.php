<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');

    Route::post('forgot-password', 'sendResetLinkEmail');
    Route::post('reset-password', 'reset')->name('password.reset');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout')->middleware('auth:sanctum');
        Route::get('profile', 'profile')->middleware('auth:sanctum');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);

    Route::post('fileUpload', [ImageController::class, 'store']);
//    Route::get('users/get-trainers', [UserController::class, 'get_trainers']);

//    Route::get('users', [UserController::class, 'index']);
//    Route::get('users/{id}', [UserController::class, 'show']);
//    Route::put('users/{id}', [UserController::class, 'update']);
//    Route::delete('users/{id}', [UserController::class, 'destroy']);
});
