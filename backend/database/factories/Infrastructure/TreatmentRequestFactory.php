<?php

namespace Database\Factories\Infrastructure;

use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentRequestFactory extends Factory
{
    protected $model = TreatmentRequest::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'urgency' => $this->faker->randomElement(['low', 'medium', 'high', 'emergency']),
            'symptoms' => [
                'pain_level' => $this->faker->numberBetween(0, 10),
                'duration' => $this->faker->randomElement(['1_day', '1_week', '1_month', '3_months']),
                'location' => $this->faker->randomElement(['upper_left', 'upper_right', 'lower_left', 'lower_right'])
            ],
            'affected_teeth' => [$this->faker->numberBetween(11, 48)], // FDI notation
            'photos' => [],
            'status' => 'pending',
            'preferred_date' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'preferred_times' => ['morning', 'afternoon'],
            'is_emergency' => $this->faker->boolean(20), // 20% chance of being emergency
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function dispatched(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'dispatched',
        ]);
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'assigned',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function emergency(): static
    {
        return $this->state(fn (array $attributes) => [
            'urgency' => 'emergency',
            'is_emergency' => true,
            'symptoms' => [
                'pain_level' => $this->faker->numberBetween(8, 10),
                'duration' => '1_day',
                'location' => $this->faker->randomElement(['upper_left', 'upper_right', 'lower_left', 'lower_right'])
            ],
        ]);
    }

    public function withPainLevel(int $level): static
    {
        return $this->state(fn (array $attributes) => [
            'symptoms' => array_merge($attributes['symptoms'] ?? [], ['pain_level' => $level]),
        ]);
    }
}