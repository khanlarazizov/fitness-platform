<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Lib\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected IUserRepository $userRepository)
    {
    }

    public function index()
    {
        $users = $this->userRepository->getAllUsers();
        if (!$users) {
            return ResponseHelper::error(message: 'Users could not found');
        }
        return ResponseHelper::success(
            message: 'Users found successfully',
            data: UserResource::collection($users)
        );
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userRepository->createUser($request->validated());
        } catch (\Exception $exception) {
            Log::error('User could not be created', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'User could not be created',
                statusCode: 400);
        }

        return ResponseHelper::success(
            message: 'User created successfully',
            data: UserResource::make($user),
            statusCode: 201);
    }

    public function show(int $id)
    {
        $user = $this->userRepository->getUserById($id);

        if (!$user) {
            return ResponseHelper::error(message: 'User could not found');
        }

        return ResponseHelper::success(
            message: 'User found successfully',
            data: UserResource::make($user)
        );
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userRepository->getUserById($id);

        if (!$user) {
            return ResponseHelper::error(message: 'User could not found');
        }

        try {
            $this->userRepository->updateUser($id, $request->validated());
        } catch (\Exception $exception) {
            Log::error('User could not be updated', ['error' => $exception->getMessage()]);
            return ResponseHelper::error(
                message: 'User could not be updated',
                statusCode: 400);
        }

        return ResponseHelper::success(
            message: 'User updated successfully',
            data: UserResource::make($user)
        );
    }

    public function destroy(int $id)
    {
        $user = $this->userRepository->getUserById($id);

        if (!$user) {
            return ResponseHelper::error(message: 'User could not be found');
        }

        $this->userRepository->deleteUser($id);
    }
}
