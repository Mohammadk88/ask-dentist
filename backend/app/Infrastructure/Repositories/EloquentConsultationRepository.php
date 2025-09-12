<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Consultation;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\DoctorId;
use App\Domain\ValueObjects\Money;
use App\Domain\Repositories\ConsultationRepositoryInterface;
use App\Models\Consultation as EloquentConsultation;

class EloquentConsultationRepository implements ConsultationRepositoryInterface
{
    public function findById(ConsultationId $id): ?Consultation
    {
        $eloquentConsultation = EloquentConsultation::find($id->getValue());

        if (!$eloquentConsultation) {
            return null;
        }

        return $this->toDomainEntity($eloquentConsultation);
    }

    public function save(Consultation $consultation): void
    {
        $eloquentConsultation = EloquentConsultation::find($consultation->getId()->getValue()) ?? new EloquentConsultation();

        $eloquentConsultation->patient_id = $consultation->getPatientId()->getValue();
        $eloquentConsultation->doctor_id = $consultation->getDoctorId()->getValue();
        $eloquentConsultation->chief_complaint = $consultation->getChiefComplaint();
        $eloquentConsultation->status = $consultation->getStatus();
        $eloquentConsultation->type = $consultation->getType();
        $eloquentConsultation->fee = $consultation->getFee()->getAmount();
        $eloquentConsultation->currency = $consultation->getFee()->getCurrency();
        $eloquentConsultation->scheduled_at = $consultation->getScheduledAt()->format('Y-m-d H:i:s');
        $eloquentConsultation->started_at = $consultation->getStartedAt()?->format('Y-m-d H:i:s');
        $eloquentConsultation->completed_at = $consultation->getCompletedAt()?->format('Y-m-d H:i:s');
        $eloquentConsultation->cancelled_at = $consultation->getCancelledAt()?->format('Y-m-d H:i:s');
        $eloquentConsultation->cancel_reason = $consultation->getCancelReason();
        $eloquentConsultation->diagnosis = $consultation->getDiagnosis();
        $eloquentConsultation->treatment_plan = $consultation->getTreatmentPlan();
        $eloquentConsultation->notes = $consultation->getNotes();
        $eloquentConsultation->symptoms = json_encode($consultation->getSymptoms());
        $eloquentConsultation->attachments = json_encode($consultation->getAttachments());

        $eloquentConsultation->save();

        // Update the domain entity with the new ID if it was just created
        if ($consultation->getId()->getValue() === 0) {
            $reflection = new \ReflectionClass($consultation);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($consultation, new ConsultationId($eloquentConsultation->id));
        }
    }

    public function delete(Consultation $consultation): void
    {
        EloquentConsultation::destroy($consultation->getId()->getValue());
    }

    public function exists(ConsultationId $id): bool
    {
        return EloquentConsultation::where('id', $id->getValue())->exists();
    }

    public function getConsultationsByPatient(PatientId $patientId): array
    {
        $eloquentConsultations = EloquentConsultation::where('patient_id', $patientId->getValue())->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    public function getConsultationsByDoctor(DoctorId $doctorId): array
    {
        $eloquentConsultations = EloquentConsultation::where('doctor_id', $doctorId->getValue())->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    public function getPendingConsultations(): array
    {
        $eloquentConsultations = EloquentConsultation::where('status', 'pending')->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    public function getConfirmedConsultations(): array
    {
        $eloquentConsultations = EloquentConsultation::where('status', 'confirmed')->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    public function getConsultationsForDate(\DateTimeImmutable $date): array
    {
        $eloquentConsultations = EloquentConsultation::whereDate('scheduled_at', $date->format('Y-m-d'))->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    public function getConsultationsBetweenDates(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        $eloquentConsultations = EloquentConsultation::whereBetween('scheduled_at', [
            $startDate->format('Y-m-d H:i:s'),
            $endDate->format('Y-m-d H:i:s')
        ])->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    public function getCompletedConsultationsByDoctor(DoctorId $doctorId): array
    {
        $eloquentConsultations = EloquentConsultation::where('doctor_id', $doctorId->getValue())
            ->where('status', 'completed')
            ->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    public function getUpcomingConsultationsByPatient(PatientId $patientId): array
    {
        $eloquentConsultations = EloquentConsultation::where('patient_id', $patientId->getValue())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', '>', now())
            ->get();

        return $eloquentConsultations->map(fn($consultation) => $this->toDomainEntity($consultation))->toArray();
    }

    private function toDomainEntity(EloquentConsultation $eloquentConsultation): Consultation
    {
        return new Consultation(
            new ConsultationId($eloquentConsultation->id),
            new PatientId($eloquentConsultation->patient_id),
            new DoctorId($eloquentConsultation->doctor_id),
            $eloquentConsultation->chief_complaint,
            $eloquentConsultation->status,
            $eloquentConsultation->type,
            new Money($eloquentConsultation->fee, $eloquentConsultation->currency),
            new \DateTimeImmutable($eloquentConsultation->scheduled_at),
            $eloquentConsultation->started_at ? new \DateTimeImmutable($eloquentConsultation->started_at) : null,
            $eloquentConsultation->completed_at ? new \DateTimeImmutable($eloquentConsultation->completed_at) : null,
            $eloquentConsultation->cancelled_at ? new \DateTimeImmutable($eloquentConsultation->cancelled_at) : null,
            $eloquentConsultation->cancel_reason,
            $eloquentConsultation->diagnosis,
            $eloquentConsultation->treatment_plan,
            $eloquentConsultation->notes,
            json_decode($eloquentConsultation->symptoms ?? '[]', true),
            json_decode($eloquentConsultation->attachments ?? '[]', true)
        );
    }
}
