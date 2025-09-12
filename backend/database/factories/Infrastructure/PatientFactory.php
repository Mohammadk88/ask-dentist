<?php

namespace Database\Factories\Infrastructure;

use App\Infrastructure\Models\Patient;
use App\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date_of_birth' => $this->faker->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other', 'prefer_not_to_say']),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'insurance_provider' => $this->faker->optional()->company(),
            'insurance_number' => $this->faker->optional()->regexify('[A-Z]{3}[0-9]{9}'),
            'medical_history' => [
                'allergies' => $this->faker->optional()->words(2, true),
                'medications' => $this->faker->optional()->words(2, true),
                'conditions' => $this->faker->optional()->words(3, true)
            ],
            'dental_history' => [
                'previous_treatments' => $this->faker->optional()->words(3, true),
                'preferences' => $this->faker->optional()->words(2, true)
            ],
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function withInsurance(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_provider' => $this->faker->company(),
            'insurance_number' => $this->faker->regexify('[A-Z]{3}[0-9]{9}'),
        ]);
    }

    public function withoutInsurance(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_provider' => null,
            'insurance_number' => null,
        ]);
    }

    public function withMedicalHistory(array $history): static
    {
        return $this->state(fn (array $attributes) => [
            'medical_history' => $history,
        ]);
    }

    public function withDentalHistory(array $history): static
    {
        return $this->state(fn (array $attributes) => [
            'dental_history' => $history,
        ]);
    }
}