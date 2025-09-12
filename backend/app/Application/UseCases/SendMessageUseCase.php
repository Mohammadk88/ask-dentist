<?php

namespace App\Application\UseCases;

use App\Application\DTOs\SendMessageDTO;
use App\Domain\Entities\Message;
use App\Domain\ValueObjects\MessageId;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\UserId;
use App\Domain\Repositories\MessageRepositoryInterface;
use App\Domain\Repositories\ConsultationRepositoryInterface;

class SendMessageUseCase
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository,
        private ConsultationRepositoryInterface $consultationRepository
    ) {}

    public function execute(SendMessageDTO $dto): Message
    {
        $consultationId = new ConsultationId($dto->consultationId);
        $senderId = new UserId($dto->senderId);

        // Validate consultation exists and is active
        $consultation = $this->consultationRepository->findById($consultationId);
        if (!$consultation) {
            throw new \DomainException('Consultation not found');
        }

        if (!$consultation->isInProgress() && !$consultation->isConfirmed()) {
            throw new \DomainException('Cannot send messages for this consultation status');
        }

        // Create message
        $message = new Message(
            new MessageId(0), // Will be set by repository
            $consultationId,
            $senderId,
            $dto->content,
            $dto->type,
            new \DateTimeImmutable()
        );

        // Add attachments
        foreach ($dto->attachments as $attachment) {
            $message->addAttachment($attachment);
        }

        // Save message
        $this->messageRepository->save($message);

        return $message;
    }
}
