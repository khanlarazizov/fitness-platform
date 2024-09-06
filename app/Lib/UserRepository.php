<?php

namespace App\Lib;

use App\Helpers\UploadHelper;
use App\Lib\Interfaces\IUserRepository;
use App\Models\Image;
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

    public function createUser(array $data): ?User
    {
        try {
            DB::beginTransaction();
            $data['password'] = Hash::make($data['password']);
            $file = UploadHelper::uploadFile($data['file']);
//        $filePath = $data['uploaded_file'];

//            $fileName = pathinfo($file, PATHINFO_FILENAME);
//            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            $user = User::create($data);

            $user->image()->create([
                'name' => $file
            ]);

            return $user->load('trainer', 'image');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('User could not be created', ['error' => $exception->getMessage()]);
            throw new \Exception('User could not be created');
//            return null;
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

    public function updateUser(int $id, array $data)
    {
        $user = User::find($id)->load('trainer');

        if (isset($data['password']))
            $data['password'] = Hash::make($data['password']);

        $user->update($data);
    }

    public function deleteUser($id)
    {
        $user = $this->getUserById($id);
        if ($user)
            return $user->delete();

        return false;
    }
}
