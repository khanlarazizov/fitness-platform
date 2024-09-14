<?php

namespace Database\Seeders;

use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(100)->create();

        $users->each(function ($user) {
            $user->assignRole('user');
        });

//        $user = User::create([
//            'name' => 'Akif',
//            'surname' => 'Akif',
//            'email' => 'admin@gmail.com',
//            'password' => Hash::make('adminadmin'),
//            'email_verified_at' => now(),
//            'phone_number' => '+99455' . rand(1111111, 9999999),
//            'status' => UserStatusEnum::ACTIVE->value,
//            'trainer_id' => null
//        ]);
//
//        $user->assignRole('admin');
//        $role = Role::find(1);
//        $permissions = Permission::all();
//        $role->syncPermissions($permissions);

//        $user = User::create([
//            'name' => 'trainer',
//            'surname' => 'trainer',
//            'email' => 'trainer@gmail.com',
//            'password' => Hash::make('trainer'),
//            'email_verified_at' => now(),
//            'phone_number' => '+99455' . rand(1111111, 9999999),
//            'status' => UserStatusEnum::ACTIVE->value,
//            'trainer_id' => null
//        ]);
//
//        $user->assignRole('trainer');
//        $role = Role::find(2);
//        $permissions = Permission::all();
//        $role->syncPermissions($permissions);
    }
}
