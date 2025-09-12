<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Doctor;
use App\Domain\ValueObjects\DoctorId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SpecializationId;
use App\Domain\ValueObjects\Money;
use App\Domain\Repositories\DoctorRepositoryInterface;
use App\Models\Doctor as EloquentDoctor;

class EloquentDoctorRepository implements DoctorRepositoryInterface
{
    public function findById(DoctorId $id): ?Doctor
    {
        $eloquentDoctor = EloquentDoctor::find($id->getValue());

        if (!$eloquentDoctor) {
            return null;
        }

        return $this->toDomainEntity($eloquentDoctor);
    }

    public function findByUserId(UserId $userId): ?Doctor
    {
        $eloquentDoctor = EloquentDoctor::where('user_id', $userId->getValue())->first();

        if (!$eloquentDoctor) {
            return null;
        }

        return $this->toDomainEntity($eloquentDoctor);
    }

    public function save(Doctor $doctor): void
    {
        $eloquentDoctor = EloquentDoctor::find($doctor->getId()->getValue()) ?? new EloquentDoctor();

        $eloquentDoctor->user_id = $doctor->getUserId()->getValue();
        $eloquentDoctor->specialization_id = $doctor->getSpecializationId()->getValue();
        $eloquentDoctor->license_number = $doctor->getLicenseNumber();
        $eloquentDoctor->years_of_experience = $doctor->getYearsOfExperience();
        $eloquentDoctor->consultation_fee = $doctor->getConsultationFee()->getAmount();
        $eloquentDoctor->currency = $doctor->getConsultationFee()->getCurrency();
        $eloquentDoctor->is_verified = $doctor->isVerified();
        $eloquentDoctor->is_available = $doctor->isAvailable();
        $eloquentDoctor->bio = $doctor->getBio();
        $eloquentDoctor->education = $doctor->getEducation();
        $eloquentDoctor->certifications = $doctor->getCertifications();

        $eloquentDoctor->save();

        // Update the domain entity with the new ID if it was just created
        if ($doctor->getId()->getValue() === 0) {
            $reflection = new \ReflectionClass($doctor);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($doctor, new DoctorId($eloquentDoctor->id));
        }
    }

    public function delete(Doctor $doctor): void
    {
        EloquentDoctor::destroy($doctor->getId()->getValue());
    }

    public function exists(DoctorId $id): bool
    {
        return EloquentDoctor::where('id', $id->getValue())->exists();
    }

    public function getVerifiedDoctors(): array
    {
        $eloquentDoctors = EloquentDoctor::where('is_verified', true)->get();

        return $eloquentDoctors->map(fn($doctor) => $this->toDomainEntity($doctor))->toArray();
    }

    public function getAvailableDoctors(): array
    {
        $eloquentDoctors = EloquentDoctor::where('is_available', true)
            ->where('is_verified', true)
            ->get();

        return $eloquentDoctors->map(fn($doctor) => $this->toDomainEntity($doctor))->toArray();
    }

    public function getDoctorsBySpecialization(SpecializationId $specializationId): array
    {
        $eloquentDoctors = EloquentDoctor::where('specialization_id', $specializationId->getValue())
            ->where('is_verified', true)
            ->get();

        return $eloquentDoctors->map(fn($doctor) => $this->toDomainEntity($doctor))->toArray();
    }

    public function searchByName(string $name): array
    {
        $eloquentDoctors = EloquentDoctor::whereHas('user', function ($query) use ($name) {
            $query->where('first_name', 'like', "%{$name}%")
                  ->orWhere('last_name', 'like', "%{$name}%");
        })->get();

        return $eloquentDoctors->map(fn($doctor) => $this->toDomainEntity($doctor))->toArray();
    }

    public function getPendingVerificationDoctors(): array
    {
        $eloquentDoctors = EloquentDoctor::where('is_verified', false)->get();

        return $eloquentDoctors->map(fn($doctor) => $this->toDomainEntity($doctor))->toArray();
    }

    private function toDomainEntity(EloquentDoctor $eloquentDoctor): Doctor
    {
        return new Doctor(
            new DoctorId($eloquentDoctor->id),
            new UserId($eloquentDoctor->user_id),
            new SpecializationId($eloquentDoctor->specialization_id),
            $eloquentDoctor->license_number,
            $eloquentDoctor->years_of_experience,
            new Money($eloquentDoctor->consultation_fee, $eloquentDoctor->currency),
            $eloquentDoctor->is_verified,
            $eloquentDoctor->is_available,
            $eloquentDoctor->bio,
            $eloquentDoctor->education,
            $eloquentDoctor->certifications
        );
    }
}
