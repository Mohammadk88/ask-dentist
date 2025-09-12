<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Phone;
use App\Domain\ValueObjects\UserId;

class User
{
    public function __construct(
        private UserId $id,
        private string $name,
        private Email $email,
        private Phone $phone,
        private string $userType,
        private bool $isActive = true,
        private ?string $profilePicture = null,
        private ?\DateTimeImmutable $emailVerifiedAt = null,
        private ?\DateTimeImmutable $lastLoginAt = null
    ) {}

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function getEmailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function getLastLoginAt(): ?\DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function updatePhone(Phone $phone): void
    {
        $this->phone = $phone;
    }

    public function updateProfilePicture(?string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }

    public function markEmailAsVerified(): void
    {
        $this->emailVerifiedAt = new \DateTimeImmutable();
    }

    public function recordLogin(): void
    {
        $this->lastLoginAt = new \DateTimeImmutable();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function isPatient(): bool
    {
        return $this->userType === 'patient';
    }

    public function isDoctor(): bool
    {
        return $this->userType === 'doctor';
    }

    public function isAdmin(): bool
    {
        return in_array($this->userType, ['admin', 'clinic_manager']);
    }
}
