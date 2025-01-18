<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->randomDigit(),
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'language_code' => fake()->languageCode(),
            'is_premium' => fake()->boolean(),
        ];
    }
}
