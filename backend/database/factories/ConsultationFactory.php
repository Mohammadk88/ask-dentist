<?php

namespace Database\Factories;

use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consultation>
 */
class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'title' => fake()->randomElement([
                'Tooth Pain',
                'Dental Cleaning',
                'Root Canal Consultation',
                'Wisdom Tooth Removal',
                'Cavity Treatment',
                'Dental Crown',
                'Orthodontic Consultation'
            ]),
            'description' => fake()->paragraph(),
            'urgency' => fake()->randomElement(['low', 'medium', 'high', 'emergency']),
            'symptoms' => [
                'pain_level' => fake()->numberBetween(1, 10),
                'duration' => fake()->randomElement(['hours', 'days', 'weeks']),
                'location' => fake()->randomElement(['upper_left', 'upper_right', 'lower_left', 'lower_right'])
            ],
            'affected_teeth' => fake()->randomElements(
                ['11', '12', '13', '21', '22', '23', '31', '32', '33', '41', '42', '43'],
                fake()->numberBetween(1, 3)
            ),
            'photos' => fake()->randomElement([
                null,
                [],
                ["image1.jpg", "image2.jpg"]
            ]),
            'status' => fake()->randomElement([
                'pending', 'reviewing', 'quote_requested', 'quoted',
                'accepted', 'scheduled', 'in_progress', 'completed'
            ]),
            'preferred_date' => fake()->dateTimeBetween('now', '+30 days'),
            'preferred_times' => ['morning', 'afternoon'],
            'is_emergency' => fake()->boolean(20), // 20% chance of being emergency
            'notes' => fake()->optional()->sentence()
        ];
    }

    /**
     * Indicate that the consultation is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the consultation is assigned.
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * Indicate that the consultation is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Indicate that the consultation is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the consultation is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
