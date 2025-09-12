<?php

namespace Database\Factories;

use App\Infrastructure\Models\Pricing;
use App\Infrastructure\Models\Service;
use App\Infrastructure\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Pricing>
 */
class PricingFactory extends Factory
{
    protected $model = Pricing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'clinic_id' => Clinic::factory(),
            'base_price' => $this->faker->randomFloat(2, 50, 500),
            'currency' => 'USD',
            'effective_from' => now()->subDays(30),
            'effective_until' => now()->addDays(365),
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Pricing without tooth modifiers
     */
    public function withoutModifiers(): static
    {
        return $this->state(fn (array $attributes) => [
            'tooth_modifier' => null,
        ]);
    }

    /**
     * Pricing with custom tooth modifiers
     */
    public function withModifiers(array $modifiers): static
    {
        return $this->state(fn (array $attributes) => [
            'tooth_modifier' => $modifiers,
        ]);
    }
}
