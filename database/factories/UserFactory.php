<?php

namespace Database\Factories;

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

        $gender = fake()->randomElement(['male', 'female']);

        return [
            'personal_number' => fake()->unique()->numberBetween(100, 9999999),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            'name' => fake()->name($gender),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'pin' => '1111',
            'gender' => $gender,
            'title' => fake()->title($gender),
            'position' => fake()->jobTitle(),
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
