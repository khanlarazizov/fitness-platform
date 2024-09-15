<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public array $permissions = [
        'index', 'store', 'show', 'update', 'destroy'
    ];

    public array $adminControllers = [
        'admin',
        'user',
        'trainer',
        'workout',
        'plan',
        'category',
        'role',
        'permission'
    ];

    public array $trainerControllers = [
        'workout',
        'plan',
        'category',
    ];

    public function run(): void
    {
        // Admin Permissions
        foreach ($this->adminControllers as $controller) {
            foreach ($this->permissions as $permission) {
                Permission::create(['name' => $controller . '-' . $permission, 'guard_name' => 'admin-api']);
            }
        }

        // TrainerController Permissions
        foreach ($this->trainerControllers as $controller) {
            foreach ($this->permissions as $permission) {
                Permission::create(['name' => $controller . '-' . $permission, 'guard_name' => 'trainer-api']);
            }
        }
    }
}
