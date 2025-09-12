<?php

namespace App\Application\DTOs;

class DispatchTreatmentRequestDTO
{
    public function __construct(
        public readonly string $treatmentRequestId,
        public readonly ?int $maxDoctors = 5
    ) {}
}