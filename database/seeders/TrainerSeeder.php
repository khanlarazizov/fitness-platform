<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Models\Trainer;
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
        $trainer = Trainer::factory(30)->create();

        $trainer->each(
            fn($trainer) => $trainer->assignRole('trainer')
        );

        $trainer2 = Trainer::create([
            'name' => 'trainer',
            'surname' => 'trainer',
            'email' => 'trainer@gmail.com',
            'password' => Hash::make('trainertrainer'),
            'phone_number' => '+994555555555',
            'gender' => GenderEnum::MALE->value,
            'birth_date' => '1990-01-01',
            'status' => StatusEnum::ACTIVE->value,
        ]);

        $trainer2->assignRole('trainer');
        $role = Role::findByName('trainer', 'trainer-api');
        $trainerPermissions = Permission::where('guard_name', 'trainer-api')->get();
        $role->syncPermissions($trainerPermissions);
    }
}
