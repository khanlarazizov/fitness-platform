<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\TrainerService;

class ChooseTrainer extends Controller
{
    public function __construct(protected TrainerService $trainerService)
    {
    }

    public function store(int $id)
    {
        $user = auth()->user();

        if (!$user) {
            return ResponseHelper::error(
                statusCode: 401
            );
        }

        try {
            $this->trainerService->assignTrainer($user, $id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: UserResource::make($user));
    }

    public function destroy()
    {
        $user = auth()->user();

        if (!$user) {
            return ResponseHelper::error(statusCode: 401);
        }

        try {
            $this->trainerService->cancelTrainer($user);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success(data: UserResource::make($user));
    }
}
