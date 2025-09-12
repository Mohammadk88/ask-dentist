<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Message;
use App\Domain\ValueObjects\MessageId;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\UserId;
use App\Domain\Repositories\MessageRepositoryInterface;
use App\Models\Message as EloquentMessage;

class EloquentMessageRepository implements MessageRepositoryInterface
{
    public function findById(MessageId $id): ?Message
    {
        $eloquentMessage = EloquentMessage::find($id->getValue());

        if (!$eloquentMessage) {
            return null;
        }

        return $this->toDomainEntity($eloquentMessage);
    }

    public function save(Message $message): void
    {
        $eloquentMessage = EloquentMessage::find($message->getId()->getValue()) ?? new EloquentMessage();

        $eloquentMessage->consultation_id = $message->getConsultationId()->getValue();
        $eloquentMessage->sender_id = $message->getSenderId()->getValue();
        $eloquentMessage->content = $message->getContent();
        $eloquentMessage->type = $message->getType();
        $eloquentMessage->sent_at = $message->getSentAt()->format('Y-m-d H:i:s');
        $eloquentMessage->is_read = $message->isRead();
        $eloquentMessage->read_at = $message->getReadAt()?->format('Y-m-d H:i:s');
        $eloquentMessage->attachments = json_encode($message->getAttachments());

        $eloquentMessage->save();

        // Update the domain entity with the new ID if it was just created
        if ($message->getId()->getValue() === 0) {
            $reflection = new \ReflectionClass($message);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($message, new MessageId($eloquentMessage->id));
        }
    }

    public function delete(Message $message): void
    {
        EloquentMessage::destroy($message->getId()->getValue());
    }

    public function exists(MessageId $id): bool
    {
        return EloquentMessage::where('id', $id->getValue())->exists();
    }

    public function getMessagesByConsultation(ConsultationId $consultationId): array
    {
        $eloquentMessages = EloquentMessage::where('consultation_id', $consultationId->getValue())
            ->orderBy('sent_at', 'asc')
            ->get();

        return $eloquentMessages->map(fn($message) => $this->toDomainEntity($message))->toArray();
    }

    public function getUnreadMessagesByUser(UserId $userId): array
    {
        $eloquentMessages = EloquentMessage::where('sender_id', '!=', $userId->getValue())
            ->where('is_read', false)
            ->whereHas('consultation', function ($query) use ($userId) {
                $query->whereHas('patient', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId->getValue());
                })->orWhereHas('doctor', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId->getValue());
                });
            })
            ->get();

        return $eloquentMessages->map(fn($message) => $this->toDomainEntity($message))->toArray();
    }

    public function getMessagesBetweenDates(ConsultationId $consultationId, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        $eloquentMessages = EloquentMessage::where('consultation_id', $consultationId->getValue())
            ->whereBetween('sent_at', [
                $startDate->format('Y-m-d H:i:s'),
                $endDate->format('Y-m-d H:i:s')
            ])
            ->orderBy('sent_at', 'asc')
            ->get();

        return $eloquentMessages->map(fn($message) => $this->toDomainEntity($message))->toArray();
    }

    public function markAllAsRead(ConsultationId $consultationId, UserId $userId): void
    {
        EloquentMessage::where('consultation_id', $consultationId->getValue())
            ->where('sender_id', '!=', $userId->getValue())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    public function getLatestMessageByConsultation(ConsultationId $consultationId): ?Message
    {
        $eloquentMessage = EloquentMessage::where('consultation_id', $consultationId->getValue())
            ->orderBy('sent_at', 'desc')
            ->first();

        if (!$eloquentMessage) {
            return null;
        }

        return $this->toDomainEntity($eloquentMessage);
    }

    private function toDomainEntity(EloquentMessage $eloquentMessage): Message
    {
        return new Message(
            new MessageId($eloquentMessage->id),
            new ConsultationId($eloquentMessage->consultation_id),
            new UserId($eloquentMessage->sender_id),
            $eloquentMessage->content,
            $eloquentMessage->type,
            new \DateTimeImmutable($eloquentMessage->sent_at),
            $eloquentMessage->is_read,
            $eloquentMessage->read_at ? new \DateTimeImmutable($eloquentMessage->read_at) : null,
            json_decode($eloquentMessage->attachments ?? '[]', true)
        );
    }
}
