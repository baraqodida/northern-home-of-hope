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
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // --- Custom Northern Home of Hope Columns ---
            'phone_number' => fake()->numerify('07########'), // Generates clean local 10-digit mobile formats
            'county' => 'Nairobi', 
            'sub_county' => fake()->randomElement(['Langata', 'Westlands', 'Kibra', 'Dagoretti', 'Embakasi']),
            'ward' => fake()->randomElement(['Ward A', 'Ward B', 'Ward C', 'Ward D']),
            'status' => 'active', // All seeded members default to active status
            'group_id' => null,   // This gets assigned rotationally inside DatabaseSeeder.php
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