<?php

namespace App\Application\DTOs;

class SendMessageDTO
{
    public function __construct(
        public readonly int $consultationId,
        public readonly int $senderId,
        public readonly string $content,
        public readonly string $type = 'text',
        public readonly array $attachments = []
    ) {}
}
