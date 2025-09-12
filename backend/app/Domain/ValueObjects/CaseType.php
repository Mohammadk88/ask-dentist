<?php

namespace App\Domain\ValueObjects;

enum CaseType: string
{
    case DENTAL = 'dental';
    case COSMETIC = 'cosmetic';

    public function getDisplayName(): string
    {
        return match($this) {
            self::DENTAL => 'Dental Treatment',
            self::COSMETIC => 'Cosmetic Treatment',
        };
    }
}
