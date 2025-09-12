<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition(): array
    {
        $favorableType = $this->faker->randomElement([Doctor::class, Clinic::class]);
        
        return [
            'user_id' => User::factory(),
            'favorable_type' => $favorableType,
            'favorable_id' => $favorableType::factory(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function forDoctor(Doctor $doctor): static
    {
        return $this->state(fn (array $attributes) => [
            'favorable_type' => Doctor::class,
            'favorable_id' => $doctor->id,
        ]);
    }

    public function forClinic(Clinic $clinic): static
    {
        return $this->state(fn (array $attributes) => [
            'favorable_type' => Clinic::class,
            'favorable_id' => $clinic->id,
        ]);
    }
}