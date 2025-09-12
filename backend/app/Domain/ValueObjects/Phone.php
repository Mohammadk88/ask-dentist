<?php

namespace App\Domain\ValueObjects;

class Phone
{
    public function __construct(
        private readonly string $value
    ) {
        // Remove all non-digit characters for validation
        $cleanPhone = preg_replace('/\D/', '', $value);

        if (empty($cleanPhone) || strlen($cleanPhone) < 10 || strlen($cleanPhone) > 15) {
            throw new \InvalidArgumentException('Phone number must be between 10 and 15 digits');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getNumericValue(): string
    {
        return preg_replace('/\D/', '', $this->value);
    }

    public function format(): string
    {
        $numeric = $this->getNumericValue();

        // Format US/Canada numbers
        if (strlen($numeric) === 10) {
            return sprintf('(%s) %s-%s',
                substr($numeric, 0, 3),
                substr($numeric, 3, 3),
                substr($numeric, 6, 4)
            );
        }

        // Format US/Canada numbers with country code
        if (strlen($numeric) === 11 && substr($numeric, 0, 1) === '1') {
            return sprintf('+1 (%s) %s-%s',
                substr($numeric, 1, 3),
                substr($numeric, 4, 3),
                substr($numeric, 7, 4)
            );
        }

        return $this->value;
    }

    public function equals(Phone $other): bool
    {
        return $this->getNumericValue() === $other->getNumericValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
