<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateConsultationDTO;
use App\Domain\Entities\Consultation;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\DoctorId;
use App\Domain\Repositories\ConsultationRepositoryInterface;
use App\Domain\Repositories\PatientRepositoryInterface;
use App\Domain\Repositories\DoctorRepositoryInterface;

class CreateConsultationUseCase
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private PatientRepositoryInterface $patientRepository,
        private DoctorRepositoryInterface $doctorRepository
    ) {}

    public function execute(CreateConsultationDTO $dto): Consultation
    {
        $patientId = new PatientId($dto->patientId);
        $doctorId = new DoctorId($dto->doctorId);

        // Validate patient exists and has consent
        $patient = $this->patientRepository->findById($patientId);
        if (!$patient) {
            throw new \DomainException('Patient not found');
        }

        if (!$patient->canCreateConsultations()) {
            throw new \DomainException('Patient has not given required consent');
        }

        // Validate doctor exists and is available
        $doctor = $this->doctorRepository->findById($doctorId);
        if (!$doctor) {
            throw new \DomainException('Doctor not found');
        }

        if (!$doctor->isAvailable()) {
            throw new \DomainException('Doctor is not available');
        }

        if (!$doctor->isVerified()) {
            throw new \DomainException('Doctor is not verified');
        }

        // Create consultation
        $consultation = new Consultation(
            new ConsultationId(0), // Will be set by repository
            $patientId,
            $doctorId,
            $dto->chiefComplaint,
            'pending',
            $dto->type,
            $doctor->getConsultationFee(),
            new \DateTimeImmutable($dto->scheduledAt)
        );

        // Add symptoms
        foreach ($dto->symptoms as $symptom) {
            $consultation->addSymptom($symptom);
        }

        // Save consultation
        $this->consultationRepository->save($consultation);

        return $consultation;
    }
}
