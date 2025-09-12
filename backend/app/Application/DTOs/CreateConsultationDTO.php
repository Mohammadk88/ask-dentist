<?php

namespace App\Application\DTOs;

class CreateConsultationDTO
{
    public function __construct(
        public readonly int $patientId,
        public readonly int $doctorId,
        public readonly string $chiefComplaint,
        public readonly string $type,
        public readonly string $scheduledAt,
        public readonly array $symptoms = []
    ) {}
}
