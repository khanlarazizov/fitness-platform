<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'admin',
            'surname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminadmin'),
            'phone_number' => null,
            'gender' => null,
            'birth_date' => null,
            'status' => StatusEnum::ACTIVE->value,
            'weight' => null,
            'height' => null,
        ]);

        $admin->assignRole(RoleEnum::ADMIN->value);
    }
}
