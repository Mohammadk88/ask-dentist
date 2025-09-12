<?php

namespace Database\Factories;

use App\Models\Story;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoryFactory extends Factory
{
    protected $model = Story::class;

    public function definition(): array
    {
        $owner_type = $this->faker->randomElement(['clinic', 'doctor']);
        
        if ($owner_type === 'clinic') {
            $owner = \App\Models\Clinic::factory()->create();
        } else {
            $owner = \App\Models\Doctor::factory()->create();
        }

        $starts_at = $this->faker->dateTimeBetween('-7 days', '+1 day');
        $expires_at = $this->faker->dateTimeBetween($starts_at, '+24 hours');

        return [
            'owner_type' => $owner_type, // Use string values 'clinic' or 'doctor', not class names
            'owner_id' => $owner->id,
            'media' => [
                [
                    'type' => $this->faker->randomElement(['image', 'video']),
                    'path' => $this->faker->imageUrl(640, 480, 'medical'),
                    'caption' => $this->faker->sentence(),
                ]
            ],
            'caption' => $this->faker->sentence(),
            'lang' => $this->faker->randomElement(['en', 'ar']),
            'starts_at' => $starts_at,
            'expires_at' => $expires_at,
            'is_ad' => $this->faker->boolean(20), // 20% chance of being an ad
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subHours(1),
            'expires_at' => now()->addHours(23),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDays(2),
            'expires_at' => now()->subDays(1),
        ]);
    }

    public function ad(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_ad' => true,
        ]);
    }

    public function organic(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_ad' => false,
        ]);
    }

    public function forDoctor(Doctor $doctor): static
    {
        return $this->state(fn (array $attributes) => [
            'owner_type' => Doctor::class,
            'owner_id' => $doctor->id,
        ]);
    }

    public function forClinic(Clinic $clinic): static
    {
        return $this->state(fn (array $attributes) => [
            'owner_type' => Clinic::class,
            'owner_id' => $clinic->id,
        ]);
    }
}