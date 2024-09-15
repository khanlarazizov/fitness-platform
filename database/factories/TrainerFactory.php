<?php

namespace Database\Factories;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trainer>
 */
class TrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'surname' => $this->faker->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('trainertrainer'),
            'birth_date' => fake()->date(),
            'phone_number' => '+99455' . rand(1111111, 9999999),
            'gender' => fake()->randomElement(GenderEnum::cases())->value,
            'status' => fake()->randomElement(StatusEnum::cases())->value,
        ];
    }
}
