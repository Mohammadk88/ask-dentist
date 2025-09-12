<?php

namespace App\Application\UseCases;

use App\Application\DTOs\RegisterDoctorDTO;
use App\Domain\Entities\User;
use App\Domain\Entities\Doctor;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\DoctorId;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Phone;
use App\Domain\ValueObjects\SpecializationId;
use App\Domain\ValueObjects\Money;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\DoctorRepositoryInterface;

class RegisterDoctorUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private DoctorRepositoryInterface $doctorRepository
    ) {}

    public function execute(RegisterDoctorDTO $dto): array
    {
        // Check if email already exists
        $email = new Email($dto->email);
        if ($this->userRepository->emailExists($email)) {
            throw new \DomainException('Email already exists');
        }

        // Create User entity
        $user = new User(
            new UserId(0), // Will be set by repository
            $dto->firstName,
            $dto->lastName,
            $email,
            new Phone($dto->phone),
            password_hash($dto->password, PASSWORD_BCRYPT),
            'doctor'
        );

        // Save user
        $this->userRepository->save($user);

        // Create Doctor entity
        $doctor = new Doctor(
            new DoctorId(0), // Will be set by repository
            $user->getId(),
            new SpecializationId($dto->specializationId),
            $dto->licenseNumber,
            (int) $dto->yearsOfExperience,
            new Money($dto->consultationFee, $dto->currency),
            false, // Not verified initially
            true, // Available by default
            $dto->bio,
            $dto->education,
            $dto->certifications
        );

        // Save doctor
        $this->doctorRepository->save($doctor);

        return [
            'user' => $user,
            'doctor' => $doctor
        ];
    }
}
