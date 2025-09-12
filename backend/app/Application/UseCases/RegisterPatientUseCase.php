<?php

namespace App\Application\UseCases;

use App\Application\DTOs\RegisterPatientDTO;
use App\Domain\Entities\User;
use App\Domain\Entities\Patient;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Phone;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\PatientRepositoryInterface;

class RegisterPatientUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PatientRepositoryInterface $patientRepository
    ) {}

    public function execute(RegisterPatientDTO $dto): array
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
            'patient'
        );

        // Save user
        $this->userRepository->save($user);

        // Create Patient entity
        $patient = new Patient(
            new PatientId(0), // Will be set by repository
            $user->getId(),
            new \DateTimeImmutable($dto->dateOfBirth),
            $dto->gender,
            $dto->emergencyContactName,
            new Phone($dto->emergencyContactPhone),
            $dto->consentTreatment,
            $dto->consentDataSharing,
            $dto->medicalHistory,
            $dto->allergies,
            $dto->currentMedications,
            $dto->dentalHistory,
            $dto->insuranceProvider,
            $dto->insuranceNumber
        );

        // Save patient
        $this->patientRepository->save($patient);

        return [
            'user' => $user,
            'patient' => $patient
        ];
    }
}
