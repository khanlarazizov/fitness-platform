<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        foreach (RoleEnum::cases() as $role) {
            $role = Role::create(['name' => $role->value, 'guard_name' => 'api']);

            $this->syncPermissionsToRole($role);
        }
    }

    private function syncPermissionsToRole(Role $role): void
    {
        $permissions = [];

        switch ($role->name) {
            case RoleEnum::ADMIN->value:
                $permissions = PermissionEnum::cases();
                break;
            case RoleEnum::TRAINER->value:
                $permissions = [
                    PermissionEnum::MANAGE_WORKOUTS,
                    PermissionEnum::MANAGE_CATEGORIES,
                    PermissionEnum::MANAGE_PLANS,
                ];
                break;
            case RoleEnum::USER->value:
                $permissions = [
                    PermissionEnum::CHOOSE_TRAINER,
                ];
                break;
        }

        $role->syncPermissions($permissions);
    }
}
