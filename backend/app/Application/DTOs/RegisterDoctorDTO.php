<?php

namespace App\Application\DTOs;

class RegisterDoctorDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $password,
        public readonly string $phone,
        public readonly int $specializationId,
        public readonly string $licenseNumber,
        public readonly string $yearsOfExperience,
        public readonly float $consultationFee,
        public readonly string $currency = 'USD',
        public readonly ?string $bio = null,
        public readonly ?string $education = null,
        public readonly ?string $certifications = null
    ) {}
}
