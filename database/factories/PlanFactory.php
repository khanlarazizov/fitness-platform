<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trainers = User::query()->trainer()->pluck('id')->toArray();
        return [
            'name' => fake()->word(),
            'description' => fake()->text(),
            'trainer_id' => fake()->randomElement($trainers),
            'status' => fake()->randomElement(StatusEnum::cases())->value,
        ];
    }
}
