<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ClinicId;
use App\Domain\ValueObjects\Rating;

class Review
{
    public function __construct(
        private int $id,
        private UserId $patientId,
        private UserId $doctorId,
        private ?ClinicId $clinicId,
        private Rating $rating,
        private ?array $answers = null,
        private ?string $comment = null,
        private ?\DateTime $publishedAt = null,
        private ?\DateTime $createdAt = null,
        private ?\DateTime $updatedAt = null
    ) {
        $this->validateComment();
    }

    public static function create(
        UserId $patientId,
        UserId $doctorId,
        Rating $rating,
        ?ClinicId $clinicId = null,
        ?array $answers = null,
        ?string $comment = null
    ): self {
        return new self(
            id: 0, // Will be set by repository
            patientId: $patientId,
            doctorId: $doctorId,
            clinicId: $clinicId,
            rating: $rating,
            answers: $answers,
            comment: $comment,
            publishedAt: null,
            createdAt: new \DateTime(),
            updatedAt: new \DateTime()
        );
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getPatientId(): UserId
    {
        return $this->patientId;
    }

    public function getDoctorId(): UserId
    {
        return $this->doctorId;
    }

    public function getClinicId(): ?ClinicId
    {
        return $this->clinicId;
    }

    public function getRating(): Rating
    {
        return $this->rating;
    }

    public function getAnswers(): ?array
    {
        return $this->answers;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
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
    public function publish(): void
    {
        if ($this->publishedAt !== null) {
            throw new \InvalidArgumentException('Review is already published');
        }

        $this->publishedAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function unpublish(): void
    {
        $this->publishedAt = null;
        $this->updatedAt = new \DateTime();
    }

    public function updateRating(Rating $rating): void
    {
        $this->rating = $rating;
        $this->updatedAt = new \DateTime();
    }

    public function updateComment(string $comment): void
    {
        $this->validateCommentString($comment);
        $this->comment = $comment;
        $this->updatedAt = new \DateTime();
    }

    public function addAnswer(string $question, string $answer): void
    {
        $this->answers = $this->answers ?? [];
        $this->answers[$question] = $answer;
        $this->updatedAt = new \DateTime();
    }

    public function isPublished(): bool
    {
        return $this->publishedAt !== null;
    }

    public function isDraft(): bool
    {
        return $this->publishedAt === null;
    }

    // Validation
    private function validateComment(): void
    {
        if ($this->comment !== null) {
            $this->validateCommentString($this->comment);
        }
    }

    private function validateCommentString(string $comment): void
    {
        if (strlen(trim($comment)) > 2000) {
            throw new \InvalidArgumentException('Review comment cannot exceed 2000 characters');
        }
    }
}
