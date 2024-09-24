<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\Plan\AssignPlan;
use App\Http\Controllers\API\Plan\AssignPlanByTrainer;
use App\Http\Controllers\API\Plan\PlanController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\UserProfileController;
use App\Http\Controllers\API\WorkoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class);
    Route::post('logout', LogoutController::class)->middleware('auth:sanctum');
    Route::post('register', [RegisterController::class, 'store']);
    Route::post('forgot-password', [PasswordController::class, 'store']);
    Route::post('reset-password', [PasswordController::class, 'update'])->name('password.reset');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('profile')
        ->controller(UserProfileController::class)
        ->group(function () {
            Route::get('/', 'show');
            Route::put('/', 'update');
        });

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    Route::middleware('admin_or_trainer')->group(function () {
        Route::apiResource('workouts', WorkoutController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('plans', PlanController::class);
    });

    Route::post('assign-plan/{plan}', [AssignPlan::class, 'store']);
    Route::delete('cancel-plan/{plan}', [AssignPlan::class, 'destroy']);

    Route::middleware('role:trainer')
        ->controller(AssignPlanByTrainer::class)
        ->group(function () {
            Route::post('assign-plan-by-trainer/{plan}', 'store');
            Route::delete('cancel-plan-by-trainer/{plan}', 'destroy');
        });
});
