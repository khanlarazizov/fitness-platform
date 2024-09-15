<?php

use App\Http\Controllers\API\Admin\AdminProfileController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PlanController;
use App\Http\Controllers\API\Trainer\TrainerProfileController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\UserProfileController;
use App\Http\Controllers\API\WorkoutController;
use App\Http\Controllers\Auth\Admin\AdminLoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\Trainer\TrainerLoginController;
use App\Http\Controllers\Auth\User\RegisteredUserController;
use App\Http\Controllers\Auth\User\UserLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Trainer\TrainerController;

Route::prefix('auth')->group(function () {
    Route::post('login', [UserLoginController::class, 'store']);
    Route::post('logout', [UserLoginController::class, 'destroy'])->middleware('auth:sanctum');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::post('forgot-password', [PasswordController::class, 'store']);
    Route::post('reset-password', [PasswordController::class, 'update'])->name('password.reset');

    Route::prefix('admin')->controller(AdminLoginController::class)->group(function () {
        Route::post('login', 'store');
        Route::post('logout', 'destroy')->middleware('auth:sanctum');
    });

    Route::prefix('trainer')->controller(TrainerLoginController::class)->group(function () {
        Route::post('login', 'store');
        Route::post('logout', 'destroy')->middleware('auth:sanctum');
    });
});

Route::middleware('auth:sanctum')
    ->prefix('users/{user}')
    ->controller(UserProfileController::class)
    ->group(function () {
        Route::get('profile', 'show');
        Route::put('profile', 'update');
    });

Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admins/{admin}')
    ->controller(AdminProfileController::class)
    ->group(function () {
        Route::get('profile', 'show');
        Route::put('profile', 'update');
    });

Route::middleware(['auth:sanctum', 'role:trainer'])
    ->prefix('trainers/{trainer}')
    ->controller(TrainerProfileController::class)
    ->group(function () {
        Route::get('profile', 'show');
        Route::put('profile', 'update');
    });

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('trainers', TrainerController::class);
});

Route::middleware(['auth:sanctum', 'admin_or_trainer'])->group(function () {
    Route::apiResource('workouts', WorkoutController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('plans', PlanController::class);
});
