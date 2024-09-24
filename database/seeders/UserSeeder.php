<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
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

        $users->each(
            fn($user) => $user->assignRole('user')
        );

        $user = User::create([
            'name' => 'john',
            'surname' => 'doe',
            'email' => 'john_doe2@gmail.com',
            'password' => Hash::make('johndoe1'),
            'phone_number' => '+99455' . rand(1111111, 9999999),
            'gender' => GenderEnum::MALE->value,
            'weight' => 80,
            'height' => 180,
            'birth_date' => '1995-01-01',
            'status' => StatusEnum::ACTIVE->value,
        ]);

        $user->assignRole('user');
    }
}
