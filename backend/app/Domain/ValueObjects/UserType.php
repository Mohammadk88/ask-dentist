<?php

namespace App\Domain\ValueObjects;

enum UserType: string
{
    case ADMIN = 'admin';
    case CLINIC_MANAGER = 'clinic_manager';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';

    public function getDisplayName(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::CLINIC_MANAGER => 'Clinic Manager',
            self::DOCTOR => 'Doctor',
            self::PATIENT => 'Patient',
        };
    }

    public function hasRole(string $role): bool
    {
        return $this->value === $role;
    }
}
