<?php

namespace Database\Factories;

use App\Models\BeforeAfterCase;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeforeAfterCaseFactory extends Factory
{
    protected $model = BeforeAfterCase::class;

    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'clinic_id' => Clinic::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'before_path' => 'before_after/before_' . $this->faker->uuid . '.jpg',
            'after_path' => 'before_after/after_' . $this->faker->uuid . '.jpg',
            'tags' => $this->faker->randomElements(['orthodontics', 'whitening', 'implants', 'veneers', 'crowns'], $this->faker->numberBetween(1, 3)),
            'treatment_type' => $this->faker->randomElement(['orthodontics', 'cosmetic', 'restorative', 'surgical']),
            'duration_days' => $this->faker->numberBetween(30, 365),
            'procedure_details' => $this->faker->paragraph(),
            'cost_range' => $this->faker->randomElement(['$500-$1000', '$1000-$2500', '$2500-$5000', '$5000+']),
            'is_featured' => $this->faker->boolean(20),
            'status' => 'published',
            'is_approved' => true,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'is_approved' => true,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'is_approved' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function unapproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    public function forDoctor(Doctor $doctor): static
    {
        return $this->state(fn (array $attributes) => [
            'doctor_id' => $doctor->id,
        ]);
    }

    public function forClinic(Clinic $clinic): static
    {
        return $this->state(fn (array $attributes) => [
            'clinic_id' => $clinic->id,
        ]);
    }
}