<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail' => $this->faker->imageUrl(),
          'name' => $this->faker->randomElement([
    'Pelatihan Digital Marketing UMKM',
    'Workshop Desain Grafis',
    'Pelatihan Manajemen UMKM',
    'Bootcamp Website Desa',
    'Workshop Branding Produk Lokal',
    'Pelatihan Budidaya Hidroponik',
    'Seminar Strategi Jualan Online',
]),
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(50000, 900000),
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
