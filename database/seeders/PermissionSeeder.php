<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }
    }
}
