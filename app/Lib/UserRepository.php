<?php

namespace App\Lib;

use App\Enums\RoleEnum;
use App\Helpers\UploadHelper;
use App\Lib\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements IUserRepository
{
    public function getAllUsers(array $data): LengthAwarePaginator
    {
        return User::with('trainer', 'image', 'roles', 'permissions')
            ->name($data['name'])
            ->surname($data['surname'])
            ->gender($data['gender'])
            ->phoneNumber($data['phone_number'])
            ->birthdayBetween($data['start_date'], $data['end_date'])
            ->status($data['status'])
            ->trainer($data['trainer_id'])
            ->weightBetween($data['start_weight'], $data['end_weight'])
            ->heightBetween($data['start_height'], $data['end_height'])
            ->sortBy($data['sort_by'], $data['direction'])
            ->paginate(10);
    }

    public function createUser(array $data): User
    {
        try {
            DB::beginTransaction();
            $data['password'] = Hash::make($data['password']);

            $file = isset($data['file']) ? UploadHelper::uploadFile($data['file']) : null;
            $role = $data['role'] ?? RoleEnum::USER->value;

            $user = User::create($data);
            $user->assignRole($role);
            $user->image()->create(['name' => $file]);
            DB::commit();
            return $user->load('trainer', 'image', 'roles');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('User could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('User could not be created');
        }
    }

    public function getUserById(int $id): ?User
    {
        try {
            return User::with('trainer', 'image', 'roles')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            Log::error('User not found', ['user_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('User not found');
        }
    }

    public function updateUser(int $id, array $data): User
    {
        try {
            DB::beginTransaction();
            $user = User::with('trainer', 'image', 'roles')->findOrFail($id);
            $data['password'] = isset($data['password']) ? Hash::make($data['password']) : $user->password;
            $role = $data['role'] ?? $user->roles->first()->value;

            if (isset($data['file'])) {
                $file = UploadHelper::updateFile($data['file'], $user);
                $user->image()->updateOrCreate(['name' => $file]);
            }

            $user->assignRole($role);
            $user->update($data);
            DB::commit();
            return $user;
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('User not found', ['user_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('User not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('User could not be updated', ['user_id' => $id, 'error' => $exception->getMessage()]);
            throw new \Exception('User could not be updated');
        }
    }

    public function deleteUser($id): void
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();
            $user->image()->delete();
            DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error('User not found', ['user_id' => $id, 'error' => $exception->getMessage()]);
            throw new ModelNotFoundException('User not found');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('User could not be deleted', ['user_id' => $id, 'error' => $exception->getMessage()]);
            throw new \Exception('User could not be deleted');
        }
    }
}
