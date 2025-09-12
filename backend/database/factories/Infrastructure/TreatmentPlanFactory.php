<?php

namespace Database\Factories\Infrastructure;

use App\Infrastructure\Models\TreatmentPlan;
use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentPlanFactory extends Factory
{
    protected $model = TreatmentPlan::class;

    public function definition(): array
    {
        $totalCost = $this->faker->randomFloat(2, 100, 5000);

        return [
            'treatment_request_id' => TreatmentRequest::factory(),
            'doctor_id' => Doctor::factory(),
            'clinic_id' => Clinic::factory(),
            'title' => $this->faker->randomElement([
                'Comprehensive Dental Examination',
                'Root Canal Treatment',
                'Tooth Extraction',
                'Dental Filling',
                'Teeth Cleaning',
                'Orthodontic Treatment',
                'Dental Crown',
                'Dental Implant'
            ]),
            'description' => $this->faker->paragraph(3),
            'diagnosis' => $this->faker->paragraph(2),
            'services' => [
                ['name' => 'Consultation', 'quantity' => 1, 'price' => 100],
                ['name' => 'X-Ray', 'quantity' => 1, 'price' => 50],
                ['name' => 'Treatment', 'quantity' => 1, 'price' => $totalCost - 150],
            ],
            'total_cost' => $totalCost,
            'currency' => 'USD',
            'estimated_duration_days' => $this->faker->numberBetween(1, 90),
            'number_of_visits' => $this->faker->numberBetween(1, 10),
            'timeline' => [
                'phase_1' => 'Initial consultation and diagnosis',
                'phase_2' => 'Treatment procedure',
                'phase_3' => 'Follow-up and recovery'
            ],
            'pre_treatment_instructions' => $this->faker->paragraph(),
            'post_treatment_instructions' => $this->faker->paragraph(),
            'risks_and_complications' => [
                'Minor discomfort',
                'Temporary sensitivity',
                'Rare allergic reactions'
            ],
            'alternatives' => [
                'Alternative treatment option 1',
                'Alternative treatment option 2'
            ],
            'status' => $this->faker->randomElement(['draft', 'submitted', 'accepted', 'rejected', 'expired']),
            'expires_at' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
        ]);
    }

    public function withCost(float $cost): static
    {
        return $this->state(fn (array $attributes) => [
            'total_cost' => $cost,
        ]);
    }

    public function withDuration(int $days): static
    {
        return $this->state(fn (array $attributes) => [
            'estimated_duration_days' => $days,
        ]);
    }
}