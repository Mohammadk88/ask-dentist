<?php

namespace App\Domain\ValueObjects;

class DoctorId
{
    public function __construct(
        private readonly int $value
    ) {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Doctor ID must be a positive integer');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(DoctorId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
