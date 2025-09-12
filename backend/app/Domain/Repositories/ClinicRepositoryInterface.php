<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Clinic;
use App\Domain\ValueObjects\ClinicId;

interface ClinicRepositoryInterface
{
    public function findById(ClinicId $id): ?Clinic;

    public function findByName(string $name): ?Clinic;

    public function findByCountryAndCity(string $country, string $city): array;

    public function save(Clinic $clinic): Clinic;

    public function delete(ClinicId $id): bool;

    public function getAllActive(): array;

    public function getWithHighCommissionRate(float $minRate): array;
}
