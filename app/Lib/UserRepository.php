<?php

namespace App\Lib;

use App\Helpers\UploadHelper;
use App\Lib\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements IUserRepository
{
    public function getAllUsers(): Collection
    {
        return User::with('trainer', 'image')->get();
    }

    public function createUser(array $data): User
    {
        DB::beginTransaction();
        try {
            $data['password'] = Hash::make($data['password']);

            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;

            $user = User::create($data);
            $user->assignRole('user');

            $user->image()->create(['name' => $file]);

            DB::commit();

            return $user;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('User could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('User could not be created');
        }
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id)->load('trainer');
    }

    public function getAllTrainers()
    {
        return User::whereNotNull('trainer_id')->get();
    }

    public function updateUser(int $id, array $data): bool
    {
        $user = User::find($id)->load('trainer');

        if (!$user) {
            Log::error('User not found');
            throw new \Exception('User not found');
        }

        DB::beginTransaction();
        try {
            if (isset($data['password']))
                $data['password'] = Hash::make($data['password']);

            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;
            $user->update($data);

            $user->image()->update(['name' => $file]);

            DB::commit();

            return $user;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('User could not be updated', ['error' => $exception->getMessage()]);
            throw new \Exception('User could not be updated');
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            Log::error('User not found');
            throw new \Exception('User not found');
        }

        DB::beginTransaction();
        try {

            $user->delete();
            $user->image()->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('User could not be deleted', ['error' => $exception->getMessage()]);
            throw new \Exception('User could not be deleted');
        }
    }
}
