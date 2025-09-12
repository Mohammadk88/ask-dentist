<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Doctor;
use App\Domain\ValueObjects\DoctorId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SpecializationId;

interface DoctorRepositoryInterface
{
    public function findById(DoctorId $id): ?Doctor;

    public function findByUserId(UserId $userId): ?Doctor;

    public function save(Doctor $doctor): void;

    public function delete(Doctor $doctor): void;

    public function exists(DoctorId $id): bool;

    public function getVerifiedDoctors(): array;

    public function getAvailableDoctors(): array;

    public function getDoctorsBySpecialization(SpecializationId $specializationId): array;

    public function searchByName(string $name): array;

    public function getPendingVerificationDoctors(): array;
}
