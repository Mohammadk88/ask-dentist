<?php

namespace App\Domain\ValueObjects;

final readonly class Rating
{
    public function __construct(
        public float $value
    ) {
        if ($value < 0.0 || $value > 5.0) {
            throw new \InvalidArgumentException('Rating must be between 0.0 and 5.0');
        }
    }

    public static function fromInteger(int $stars): self
    {
        if ($stars < 1 || $stars > 5) {
            throw new \InvalidArgumentException('Star rating must be between 1 and 5');
        }

        return new self((float) $stars);
    }

    public function toStars(): int
    {
        return (int) round($this->value);
    }

    public function equals(Rating $other): bool
    {
        return abs($this->value - $other->value) < 0.01;
    }

    public function __toString(): string
    {
        return number_format($this->value, 2);
    }
}
