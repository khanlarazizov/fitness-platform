<?php

namespace App\Lib;

use App\Lib\Interfaces\IAdminRepository;
use App\Models\Admin;

class AdminRepository implements IAdminRepository
{

    public function getAllAdmins()
    {
        return Admin::all();
    }

    public function getAdminById(int $id)
    {
        return Admin::find($id);
    }

    public function createAdmin(array $data)
    {
        $admin = Admin::create($data);
        $admin->assignRole('admin');
        return $admin;
    }

    public function updateAdmin(int $id, array $data)
    {
        $trainer = Admin::find($id);
        $trainer->update($data);
        return $trainer;
    }

    public function deleteAdmin(int $id)
    {
        Admin::find($id)->delete();
    }
}
