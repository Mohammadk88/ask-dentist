<?php

namespace App\Domain\ValueObjects;

class SpecializationId
{
    public function __construct(
        private readonly int $value
    ) {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Specialization ID must be a positive integer');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(SpecializationId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
