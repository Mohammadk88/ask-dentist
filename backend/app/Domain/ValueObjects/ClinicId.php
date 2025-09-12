<?php

namespace App\Domain\ValueObjects;

final readonly class ClinicId
{
    public function __construct(
        public int $value
    ) {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Clinic ID must be a positive integer');
        }
    }

    public function equals(ClinicId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
