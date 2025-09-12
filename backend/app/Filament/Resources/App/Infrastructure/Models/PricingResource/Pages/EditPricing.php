<?php

namespace App\Filament\Resources\App\Infrastructure\Models\PricingResource\Pages;

use App\Filament\Resources\App\Infrastructure\Models\PricingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPricing extends EditRecord
{
    protected static string $resource = PricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
