<?php

namespace App\Lib\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserRepository
{
    public function getAllUsers(array $data): LengthAwarePaginator;

    public function createUser(array $data): User;

    public function getUserById(int $id): ?User;

    public function updateUser(int $id, array $data): User;

    public function deleteUser($id): void;
}
