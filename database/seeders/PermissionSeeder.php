<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
//    public array $permissions = [
//        'index', 'store', 'show', 'update', 'destroy'
//    ];
//    public array $controllers = [
//        'user', 'role', 'permission', 'workout', 'category', 'trainer', 'plan'
//    ];

    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::create(['name' => $permission->label(), 'guard_name' => 'api']);
        }
    }
}
