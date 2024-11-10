<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Lib\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(protected IUserRepository $userRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $users = $this->userRepository->getAllUsers();
            return ResponseHelper::success(data: UserResource::collection($users));
        } catch (\Exception $exception) {
            return ResponseHelper::error(message: $exception->getMessage());
        }
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->createUser($request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400);
        }

        return ResponseHelper::success(data: UserResource::make($user), statusCode: 201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserById($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(message: $exception->getMessage());
        }

        return ResponseHelper::success(data: UserResource::make($user));
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->updateUser($id, $request->validated());
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400);
        }

        return ResponseHelper::success(data: UserResource::make($user));
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userRepository->deleteUser($id);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                message: $exception->getMessage(),
                statusCode: 400
            );
        }

        return ResponseHelper::success();
    }
}
