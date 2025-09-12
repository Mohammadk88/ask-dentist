<?php

namespace Database\Factories;

use App\Infrastructure\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['General', 'Preventive', 'Restorative', 'Surgical', 'Orthodontic', 'Cosmetic']),
            'duration_minutes' => $this->faker->numberBetween(15, 120),
            'is_tooth_specific' => $this->faker->boolean(70), // 70% chance of being tooth-specific
            'requires_specialist' => $this->faker->boolean(30),
            'is_emergency' => $this->faker->boolean(10),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Service that is tooth-specific
     */
    public function toothSpecific(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_tooth_specific' => true,
        ]);
    }

    /**
     * Service that is not tooth-specific
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_tooth_specific' => false,
        ]);
    }
}
