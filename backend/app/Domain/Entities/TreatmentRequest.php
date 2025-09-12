<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\TreatmentRequestId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CaseType;

class TreatmentRequest
{
    public function __construct(
        private TreatmentRequestId $id,
        private UserId $patientId,
        private CaseType $caseType,
        private string $message,
        private ?array $images = null,
        private string $status = 'pending',
        private ?\DateTime $createdAt = null,
        private ?\DateTime $updatedAt = null
    ) {
        $this->validateMessage();
        $this->validateStatus();
    }

    public static function create(
        UserId $patientId,
        CaseType $caseType,
        string $message,
        ?array $images = null
    ): self {
        return new self(
            id: new TreatmentRequestId(0), // Will be set by repository
            patientId: $patientId,
            caseType: $caseType,
            message: $message,
            images: $images,
            status: 'pending',
            createdAt: new \DateTime(),
            updatedAt: new \DateTime()
        );
    }

    // Getters
    public function getId(): TreatmentRequestId
    {
        return $this->id;
    }

    public function getPatientId(): UserId
    {
        return $this->patientId;
    }

    public function getCaseType(): CaseType
    {
        return $this->caseType;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    // Business methods
    public function sendToDoctors(): void
    {
        if ($this->status !== 'pending') {
            throw new \InvalidArgumentException('Only pending requests can be sent to doctors');
        }

        $this->status = 'sent_to_doctors';
        $this->updatedAt = new \DateTime();
    }

    public function markAsPlanned(): void
    {
        if (!in_array($this->status, ['pending', 'sent_to_doctors'])) {
            throw new \InvalidArgumentException('Request cannot be marked as planned in current status');
        }

        $this->status = 'planned';
        $this->updatedAt = new \DateTime();
    }

    public function close(): void
    {
        $this->status = 'closed';
        $this->updatedAt = new \DateTime();
    }

    public function addImage(string $imagePath): void
    {
        $this->images = $this->images ?? [];
        $this->images[] = $imagePath;
        $this->updatedAt = new \DateTime();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSentToDoctors(): bool
    {
        return $this->status === 'sent_to_doctors';
    }

    public function isPlanned(): bool
    {
        return $this->status === 'planned';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    // Validation
    private function validateMessage(): void
    {
        if (empty(trim($this->message))) {
            throw new \InvalidArgumentException('Treatment request message cannot be empty');
        }
    }

    private function validateStatus(): void
    {
        $validStatuses = ['pending', 'sent_to_doctors', 'planned', 'closed'];
        if (!in_array($this->status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid treatment request status');
        }
    }
}
