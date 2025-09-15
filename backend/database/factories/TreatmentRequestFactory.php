<?php

namespace Database\Factories;

use App\Models\TreatmentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TreatmentRequest>
 */
class TreatmentRequestFactory extends Factory
{
    protected $model = TreatmentRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'patient_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'urgency' => $this->faker->randomElement(['low', 'medium', 'high', 'emergency']),
            'symptoms' => [
                'pain_level' => $this->faker->numberBetween(1, 10),
                'duration' => $this->faker->randomElement(['hours', 'days', 'weeks']),
                'location' => $this->faker->randomElement(['upper_left', 'upper_right', 'lower_left', 'lower_right'])
            ],
            'affected_teeth' => $this->faker->randomElements(['11', '12', '13', '21', '22', '23', '31', '32', '33', '41', '42', '43'], 2),
            'photos' => [],
            'status' => $this->faker->randomElement(['pending', 'reviewing', 'quoted', 'accepted', 'scheduled']),
            'preferred_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'preferred_times' => $this->faker->randomElements(['morning', 'afternoon', 'evening'], 2),
            'is_emergency' => $this->faker->boolean(20), // 20% chance of emergency
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function pending(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function accepted(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    public function emergency(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'urgency' => 'emergency',
            'is_emergency' => true,
        ]);
    }
}
