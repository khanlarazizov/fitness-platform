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

    public array $controllers = [
        'user', 'role', 'permission'
    ];

    public function run(): void
    {
        foreach ($this->controllers as $controller) {
            foreach ($this->permissions as $permission) {
                Permission::create(['name' => $controller . '-' . $permission, 'guard_name' => 'api']);
            }
        }
    }
}
