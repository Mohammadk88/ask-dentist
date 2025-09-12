<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Message;
use App\Domain\ValueObjects\MessageId;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\UserId;

interface MessageRepositoryInterface
{
    public function findById(MessageId $id): ?Message;

    public function save(Message $message): void;

    public function delete(Message $message): void;

    public function exists(MessageId $id): bool;

    public function getMessagesByConsultation(ConsultationId $consultationId): array;

    public function getUnreadMessagesByUser(UserId $userId): array;

    public function getMessagesBetweenDates(ConsultationId $consultationId, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array;

    public function markAllAsRead(ConsultationId $consultationId, UserId $userId): void;

    public function getLatestMessageByConsultation(ConsultationId $consultationId): ?Message;
}
