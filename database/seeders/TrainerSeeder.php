<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainer = User::factory(30)->create();

        $trainer->each(
            fn($trainer) => $trainer->assignRole(RoleEnum::TRAINER->value)
        );

        $trainer2 = User::create([
            'name' => 'trainer',
            'surname' => 'trainer',
            'email' => 'trainer@gmail.com',
            'password' => Hash::make('trainertrainer'),
            'phone_number' => '+994555555555',
            'gender' => GenderEnum::MALE->value,
            'birth_date' => '1990-01-01',
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $trainer2->assignRole(RoleEnum::TRAINER->value);
    }
}
