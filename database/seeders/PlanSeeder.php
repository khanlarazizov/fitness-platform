<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Workout;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::factory()
            ->count(10)
            ->create()
            ->each(function ($plan) {
                // For each Plan, attach random Workouts
                $workouts = Workout::factory()->count(10)->create(); // Create 5 Workouts for each Plan
                $plan->workouts()->attach($workouts->pluck('id')->toArray()); // Attach workouts
            });
    }
}
