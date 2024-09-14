<?php

namespace Database\Seeders;

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
        $traine = Trainer::create([
            'name' => 'trainer',
            'surname' => 'trainer',
            'email' => 'trainer@gmail.com',
            'password' => Hash::make('trainertrainer'),
        ]);

        $traine->assignRole('trainer');
        $role = Role::findByName('trainer', 'trainer-api');
        $trainerPermissions = Permission::where('guard_name', 'trainer-api')->get();
        $role->syncPermissions($trainerPermissions);
    }
}
