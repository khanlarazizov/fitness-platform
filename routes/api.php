<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\Plan\AssignPlan;
use App\Http\Controllers\API\Plan\PlanController;
use App\Http\Controllers\API\Plan\TrainerPlanController;
use App\Http\Controllers\API\User\ChooseTrainer;
use App\Http\Controllers\API\User\TrainerStudentController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\UserProfileController;
use App\Http\Controllers\API\WorkoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts');
});
//Route::apiResource('categories', CategoryController::class);

Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class);
    Route::post('logout', LogoutController::class)->middleware('auth:sanctum');
    Route::post('register', [RegisterController::class, 'store']);
    Route::post('forgot-password', [PasswordController::class, 'store']);
    Route::post('reset-password', [PasswordController::class, 'update'])->name('password.reset');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('choose-trainer/{id}', [ChooseTrainer::class, 'store']);
    Route::delete('cancel-trainer', [ChooseTrainer::class, 'destroy']);

    Route::get('profile/', [UserProfileController::class, 'show']);
    Route::put('profile/', [UserProfileController::class, 'update']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('plans', PlanController::class);
    });

    Route::middleware('admin_or_trainer')->group(function () {
        Route::apiResource('workouts', WorkoutController::class);
        Route::apiResource('categories', CategoryController::class);
    });

    Route::middleware('role:trainer')
        ->group(function () {
            Route::apiResource('my-plans', TrainerPlanController::class);

            Route::prefix('my-students')
                ->controller(TrainerStudentController::class)
                ->group(function () {
                    Route::get('/', 'index');
                    Route::get('{user}', 'show');
                    Route::delete('{user}', 'destroy');
                });

            Route::post('assign-plan/{plan}', [AssignPlan::class, 'store']);
            Route::delete('cancel-plan/{plan}', [AssignPlan::class, 'destroy']);
        });
});
