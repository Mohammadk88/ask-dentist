<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\DoctorId;
use App\Domain\ValueObjects\Money;

class Consultation
{
    public function __construct(
        private ConsultationId $id,
        private PatientId $patientId,
        private DoctorId $doctorId,
        private string $chiefComplaint,
        private string $status,
        private string $type,
        private Money $fee,
        private \DateTimeImmutable $scheduledAt,
        private ?\DateTimeImmutable $startedAt = null,
        private ?\DateTimeImmutable $completedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null,
        private ?string $cancelReason = null,
        private ?string $diagnosis = null,
        private ?string $treatmentPlan = null,
        private ?string $notes = null,
        private array $symptoms = [],
        private array $attachments = []
    ) {}

    public function getId(): ConsultationId
    {
        return $this->id;
    }

    public function getPatientId(): PatientId
    {
        return $this->patientId;
    }

    public function getDoctorId(): DoctorId
    {
        return $this->doctorId;
    }

    public function getChiefComplaint(): string
    {
        return $this->chiefComplaint;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFee(): Money
    {
        return $this->fee;
    }

    public function getScheduledAt(): \DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function getCancelledAt(): ?\DateTimeImmutable
    {
        return $this->cancelledAt;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    public function getTreatmentPlan(): ?string
    {
        return $this->treatmentPlan;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getSymptoms(): array
    {
        return $this->symptoms;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRescheduled(): bool
    {
        return $this->status === 'rescheduled';
    }

    public function canBeStarted(): bool
    {
        return $this->isConfirmed() && $this->scheduledAt <= new \DateTimeImmutable();
    }

    public function canBeCompleted(): bool
    {
        return $this->isInProgress();
    }

    public function canBeCancelled(): bool
    {
        return $this->isPending() || $this->isConfirmed();
    }

    public function canBeRescheduled(): bool
    {
        return $this->isPending() || $this->isConfirmed();
    }

    public function start(): void
    {
        if (!$this->canBeStarted()) {
            throw new \DomainException('Consultation cannot be started in current status');
        }

        $this->status = 'in_progress';
        $this->startedAt = new \DateTimeImmutable();
    }

    public function complete(string $diagnosis, string $treatmentPlan, ?string $notes = null): void
    {
        if (!$this->canBeCompleted()) {
            throw new \DomainException('Consultation cannot be completed in current status');
        }

        $this->status = 'completed';
        $this->completedAt = new \DateTimeImmutable();
        $this->diagnosis = $diagnosis;
        $this->treatmentPlan = $treatmentPlan;
        $this->notes = $notes;
    }

    public function cancel(string $reason): void
    {
        if (!$this->canBeCancelled()) {
            throw new \DomainException('Consultation cannot be cancelled in current status');
        }

        $this->status = 'cancelled';
        $this->cancelledAt = new \DateTimeImmutable();
        $this->cancelReason = $reason;
    }

    public function reschedule(\DateTimeImmutable $newScheduledAt): void
    {
        if (!$this->canBeRescheduled()) {
            throw new \DomainException('Consultation cannot be rescheduled in current status');
        }

        $this->status = 'rescheduled';
        $this->scheduledAt = $newScheduledAt;
    }

    public function confirm(): void
    {
        if (!$this->isPending()) {
            throw new \DomainException('Only pending consultations can be confirmed');
        }

        $this->status = 'confirmed';
    }

    public function addSymptom(string $symptom): void
    {
        if (!in_array($symptom, $this->symptoms)) {
            $this->symptoms[] = $symptom;
        }
    }

    public function addAttachment(string $attachmentPath): void
    {
        if (!in_array($attachmentPath, $this->attachments)) {
            $this->attachments[] = $attachmentPath;
        }
    }

    public function getDuration(): ?int
    {
        if ($this->startedAt && $this->completedAt) {
            return $this->completedAt->getTimestamp() - $this->startedAt->getTimestamp();
        }

        return null;
    }

    public function isEmergency(): bool
    {
        return $this->type === 'emergency';
    }

    public function isRegular(): bool
    {
        return $this->type === 'regular';
    }

    public function isFollowUp(): bool
    {
        return $this->type === 'followup';
    }
}
