<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'admin',
            'surname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminadmin'),
        ]);

        $admin->assignRole('admin');
        $role = Role::findByName('admin', 'admin-api');
        $adminPermissions = Permission::where('guard_name', 'admin-api')->get();
        $role->syncPermissions($adminPermissions);
    }
}
