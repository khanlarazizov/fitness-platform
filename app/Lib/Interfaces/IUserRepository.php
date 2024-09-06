<?php

namespace App\Lib\Interfaces;

use App\Models\User;
use Illuminate\Support\Collection;

interface IUserRepository
{
    public function getAllUsers();

    public function createUser(array $data): ?User;

    public function getUserById(int $id): ?User;

    public function getAllTrainers();

    public function updateUser(int $id, array $data);

    public function deleteUser($id);
}
