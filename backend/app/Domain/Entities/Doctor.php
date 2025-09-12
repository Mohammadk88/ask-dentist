<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DoctorId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SpecializationId;
use App\Domain\ValueObjects\Money;

class Doctor
{
    public function __construct(
        private DoctorId $id,
        private UserId $userId,
        private SpecializationId $specializationId,
        private string $licenseNumber,
        private int $yearsOfExperience,
        private string $education,
        private ?string $certifications = null,
        private ?string $bio = null,
        private Money $consultationFee = new Money(0),
        private bool $isVerified = false,
        private bool $isAvailable = true
    ) {}

    public function getId(): DoctorId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getSpecializationId(): SpecializationId
    {
        return $this->specializationId;
    }

    public function getLicenseNumber(): string
    {
        return $this->licenseNumber;
    }

    public function getYearsOfExperience(): int
    {
        return $this->yearsOfExperience;
    }

    public function getEducation(): string
    {
        return $this->education;
    }

    public function getCertifications(): ?string
    {
        return $this->certifications;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function getConsultationFee(): Money
    {
        return $this->consultationFee;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function verify(): void
    {
        $this->isVerified = true;
    }

    public function unverify(): void
    {
        $this->isVerified = false;
    }

    public function makeAvailable(): void
    {
        $this->isAvailable = true;
    }

    public function makeUnavailable(): void
    {
        $this->isAvailable = false;
    }

    public function updateBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function updateConsultationFee(Money $fee): void
    {
        $this->consultationFee = $fee;
    }

    public function updateSpecialization(SpecializationId $specializationId): void
    {
        $this->specializationId = $specializationId;
    }

    public function updateEducation(string $education): void
    {
        $this->education = $education;
    }

    public function updateCertifications(?string $certifications): void
    {
        $this->certifications = $certifications;
    }

    public function canAcceptConsultations(): bool
    {
        return $this->isVerified && $this->isAvailable;
    }
}
