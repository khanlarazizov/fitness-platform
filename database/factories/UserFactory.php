<?php

namespace Database\Factories;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Models\Trainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trainers = User::query()->allTrainers()->pluck('id')->toArray();

        return [
            'name' => fake()->name(),
            'surname' => fake()->name(),
            'email' => fake()->unique()->freeEmail(),
            'remember_token' => Str::random(10),
            'password' => static::$password ??= Hash::make('password'),
            'gender' => fake()->randomElement(GenderEnum::cases())->value,
            'status' => fake()->randomElement(StatusEnum::cases())->value,
            'birth_date' => fake()->date(),
            'phone_number' => '+99455' . rand(1111111, 9999999),
            'trainer_id' => fake()->randomElement($trainers),
            'weight' => fake()->numberBetween(40, 150),
            'height' => fake()->numberBetween(130, 250),
//            'file' => fake()->image(storage_path('images'), 50, 50)
            'email_verified_at' => now(),
            'about' => fake()->text(),
            'ideal_weight' => fake()->numberBetween(40, 150),
            'target_weight' => fake()->numberBetween(40, 150),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
