<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->patient(),
            'date_of_birth' => fake()->dateTimeBetween('-80 years', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'medical_history' => [
                'allergies' => fake()->randomElement([[], ['penicillin'], ['latex', 'ibuprofen']]),
                'medications' => fake()->randomElement([[], ['aspirin'], ['metformin', 'lisinopril']]),
                'conditions' => fake()->randomElement([[], ['diabetes'], ['hypertension', 'asthma']]),
            ],
            'dental_history' => [
                'last_cleaning' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'previous_issues' => fake()->randomElement([[], ['cavities'], ['gum disease', 'tooth extraction']]),
            ],
            'insurance_provider' => fake()->randomElement(['Blue Cross', 'Aetna', 'Cigna', 'MetLife', null]),
            'insurance_number' => fake()->optional()->regexify('[A-Z]{3}[0-9]{6}'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
