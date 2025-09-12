<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\MessageId;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\UserId;

class Message
{
    public function __construct(
        private MessageId $id,
        private ConsultationId $consultationId,
        private UserId $senderId,
        private string $content,
        private string $type,
        private \DateTimeImmutable $sentAt,
        private bool $isRead = false,
        private ?\DateTimeImmutable $readAt = null,
        private array $attachments = []
    ) {}

    public function getId(): MessageId
    {
        return $this->id;
    }

    public function getConsultationId(): ConsultationId
    {
        return $this->consultationId;
    }

    public function getSenderId(): UserId
    {
        return $this->senderId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSentAt(): \DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function isSystem(): bool
    {
        return $this->type === 'system';
    }

    public function markAsRead(): void
    {
        if (!$this->isRead) {
            $this->isRead = true;
            $this->readAt = new \DateTimeImmutable();
        }
    }

    public function addAttachment(string $attachmentPath): void
    {
        if (!in_array($attachmentPath, $this->attachments)) {
            $this->attachments[] = $attachmentPath;
        }
    }

    public function hasAttachments(): bool
    {
        return !empty($this->attachments);
    }

    public function getAge(): int
    {
        return (new \DateTimeImmutable())->getTimestamp() - $this->sentAt->getTimestamp();
    }

    public function isOlderThan(int $seconds): bool
    {
        return $this->getAge() > $seconds;
    }
}
