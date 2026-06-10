<?php

namespace Database\Factories;

use App\Models\Contribution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contribution>
 */
class ContributionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'user_id' => \App\Models\User::factory(), // Automatically creates a fake member first
        'amount' => fake()->randomFloat(2, 500, 5000), // Generates a random amount between 500 and 5000
        'purpose' => fake()->randomElement(['Monthly Subscription', 'Humanitarian Support', 'Medical Fund Aid']),
        'status' => fake()->randomElement(['completed', 'completed', 'pending']), // Mostly completed, some pending
    ];
}
}
