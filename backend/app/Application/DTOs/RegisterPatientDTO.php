<?php

namespace App\Application\DTOs;

class RegisterPatientDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $password,
        public readonly string $phone,
        public readonly string $dateOfBirth,
        public readonly string $gender,
        public readonly string $emergencyContactName,
        public readonly string $emergencyContactPhone,
        public readonly bool $consentTreatment,
        public readonly bool $consentDataSharing,
        public readonly ?string $medicalHistory = null,
        public readonly ?string $allergies = null,
        public readonly ?string $currentMedications = null,
        public readonly ?string $dentalHistory = null,
        public readonly ?string $insuranceProvider = null,
        public readonly ?string $insuranceNumber = null
    ) {}
}
