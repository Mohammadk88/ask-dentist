<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Consultation;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\DoctorId;

interface ConsultationRepositoryInterface
{
    public function findById(ConsultationId $id): ?Consultation;

    public function save(Consultation $consultation): void;

    public function delete(Consultation $consultation): void;

    public function exists(ConsultationId $id): bool;

    public function getConsultationsByPatient(PatientId $patientId): array;

    public function getConsultationsByDoctor(DoctorId $doctorId): array;

    public function getPendingConsultations(): array;

    public function getConfirmedConsultations(): array;

    public function getConsultationsForDate(\DateTimeImmutable $date): array;

    public function getConsultationsBetweenDates(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array;

    public function getCompletedConsultationsByDoctor(DoctorId $doctorId): array;

    public function getUpcomingConsultationsByPatient(PatientId $patientId): array;
}
