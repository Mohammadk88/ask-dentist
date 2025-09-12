<?php

namespace App\Filament\Resources\App\Infrastructure\Models\PricingResource\Pages;

use App\Filament\Resources\App\Infrastructure\Models\PricingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePricing extends CreateRecord
{
    protected static string $resource = PricingResource::class;
}
