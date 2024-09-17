<?php

namespace App\Lib\Interfaces;

interface IAdminRepository
{
    public function getAllAdmins();

    public function getAdminById(int $id);

    public function createAdmin(array $data);

    public function updateAdmin(int $id, array $data);

    public function deleteAdmin(int $id);
}
