<?php

namespace Database\Factories\Infrastructure;

use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'license_number' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{6}'),
            'specialty' => $this->faker->randomElement([
                'general',
                'orthodontics',
                'oral_surgery',
                'endodontics',
                'periodontics',
                'prosthodontics',
                'pediatric',
                'cosmetic',
                'implantology'
            ]),
            'years_experience' => $this->faker->numberBetween(1, 30),
            'bio' => $this->faker->paragraph(3),
            'qualifications' => [$this->faker->text(50), $this->faker->text(50)],
            'languages' => ['en', $this->faker->randomElement(['es', 'fr', 'de'])],
            'rating' => $this->faker->randomFloat(2, 3.0, 5.0),
            'total_reviews' => $this->faker->numberBetween(0, 500),
            'accepts_emergency' => $this->faker->boolean(30),
            'verified_at' => now(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => null,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => now(),
        ]);
    }

    public function withRating(float $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating,
        ]);
    }

    public function withExperience(int $years): static
    {
        return $this->state(fn (array $attributes) => [
            'years_experience' => $years,
        ]);
    }
}