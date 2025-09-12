<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Patient;
use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\UserId;

interface PatientRepositoryInterface
{
    public function findById(PatientId $id): ?Patient;

    public function findByUserId(UserId $userId): ?Patient;

    public function save(Patient $patient): void;

    public function delete(Patient $patient): void;

    public function exists(PatientId $id): bool;

    public function getPatientsWithConsent(): array;

    public function searchByName(string $name): array;

    public function getPatientsRegisteredAfter(\DateTimeImmutable $date): array;
}
