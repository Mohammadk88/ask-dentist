<?php

namespace App\Domain\ValueObjects;

final readonly class TreatmentRequestId
{
    public function __construct(
        public int $value
    ) {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Treatment Request ID must be a positive integer');
        }
    }

    public function equals(TreatmentRequestId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
