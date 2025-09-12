<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Phone;

class Patient
{
    public function __construct(
        private PatientId $id,
        private UserId $userId,
        private \DateTimeImmutable $dateOfBirth,
        private string $gender,
        private string $emergencyContactName,
        private Phone $emergencyContactPhone,
        private bool $consentTreatment,
        private bool $consentDataSharing,
        private ?string $medicalHistory = null,
        private ?string $allergies = null,
        private ?string $currentMedications = null,
        private ?string $dentalHistory = null,
        private ?string $insuranceProvider = null,
        private ?string $insuranceNumber = null
    ) {}

    public function getId(): PatientId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getDateOfBirth(): \DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getEmergencyContactName(): string
    {
        return $this->emergencyContactName;
    }

    public function getEmergencyContactPhone(): Phone
    {
        return $this->emergencyContactPhone;
    }

    public function hasGivenConsent(): bool
    {
        return $this->consentTreatment && $this->consentDataSharing;
    }

    public function hasGivenTreatmentConsent(): bool
    {
        return $this->consentTreatment;
    }

    public function hasGivenDataSharingConsent(): bool
    {
        return $this->consentDataSharing;
    }

    public function getMedicalHistory(): ?string
    {
        return $this->medicalHistory;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function getCurrentMedications(): ?string
    {
        return $this->currentMedications;
    }

    public function getDentalHistory(): ?string
    {
        return $this->dentalHistory;
    }

    public function getInsuranceProvider(): ?string
    {
        return $this->insuranceProvider;
    }

    public function getInsuranceNumber(): ?string
    {
        return $this->insuranceNumber;
    }

    public function getAge(): int
    {
        return $this->dateOfBirth->diff(new \DateTimeImmutable())->y;
    }

    public function updateConsent(bool $treatmentConsent, bool $dataSharingConsent): void
    {
        $this->consentTreatment = $treatmentConsent;
        $this->consentDataSharing = $dataSharingConsent;
    }

    public function updateMedicalHistory(?string $medicalHistory): void
    {
        $this->medicalHistory = $medicalHistory;
    }

    public function updateAllergies(?string $allergies): void
    {
        $this->allergies = $allergies;
    }

    public function updateCurrentMedications(?string $medications): void
    {
        $this->currentMedications = $medications;
    }

    public function updateDentalHistory(?string $dentalHistory): void
    {
        $this->dentalHistory = $dentalHistory;
    }

    public function updateInsurance(?string $provider, ?string $number): void
    {
        $this->insuranceProvider = $provider;
        $this->insuranceNumber = $number;
    }

    public function updateEmergencyContact(string $name, Phone $phone): void
    {
        $this->emergencyContactName = $name;
        $this->emergencyContactPhone = $phone;
    }

    public function canCreateConsultations(): bool
    {
        return $this->hasGivenConsent();
    }
}
