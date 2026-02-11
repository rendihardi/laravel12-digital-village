<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialAssistance>
 */
class SocialAssistanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['bantuan pangan', 'bantuan tunai', 'bantuan kesehatan', 'bantuan subsidisi bahan bakar']),
            'thumbnail' => $this->faker->imageUrl(),
            'category' => $this->faker->randomElement(['staple', 'cash', 'health', 'subsidized fuel']),
            'description' => $this->faker->text(),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'provider' => $this->faker->company(),
            'is_available' => $this->faker->boolean(),
        ];
    }
}
