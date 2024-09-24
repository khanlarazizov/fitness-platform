<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\PermissionEnum;
use App\Enums\StatusEnum;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainer = User::factory(30)->create();

        $role = Role::find(2);
        $permissions = Permission::whereIn('name',
            [
                PermissionEnum::MANAGE_WORKOUTS->label(),
                PermissionEnum::MANAGE_CATEGORIES->label(),
                PermissionEnum::MANAGE_PLANS->label(),
            ]);
        $role->syncPermissions($permissions);

        $trainer->each(
            fn($trainer) => $trainer->assignRole('trainer')
        );

//        $trainer = User::create([
//            'name' => 'trainer',
//            'surname' => 'trainer',
//            'email' => 'trainer@gmail.com',
//            'password' => Hash::make('trainertrainer'),
//            'phone_number' => '+994555555555',
//            'gender' => GenderEnum::MALE->value,
//            'birth_date' => '1990-01-01',
//            'status' => StatusEnum::ACTIVE->value,
//        ]);

//        $trainer->assignRole('trainer');
//        $role = Role::find(2);
//        $permissions = Permission::whereIn('name',
//            [
//                'trainer-*',
//            ]
//        )->get();
//        $role->syncPermissions($permissions);
    }
}
