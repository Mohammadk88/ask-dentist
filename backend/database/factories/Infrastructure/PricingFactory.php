<?php

namespace Database\Factories\Infrastructure;

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
        // Create a service manually instead of using factory
        $service = \App\Infrastructure\Models\Service::create([
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'category' => 'general',
            'duration_minutes' => 30,
            'is_tooth_specific' => true,
        ]);

        return [
            'service_id' => $service->id,
            'clinic_id' => \App\Infrastructure\Models\Clinic::factory(),
            'base_price' => $this->faker->randomFloat(2, 50, 500),
            'currency' => 'USD',
            'discount_percentage' => 0,
            'valid_from' => now()->subDays(30),
            'valid_until' => now()->addDays(365),
            'conditions' => null,
            'is_negotiable' => false,
            'tooth_modifier' => [
                'molar' => 1.5,
                'premolar' => 1.2,
                'canine' => 1.1,
                'incisor' => 1.0,
            ],
            'notes' => null,
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
