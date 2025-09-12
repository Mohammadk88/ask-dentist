<?php

namespace App\Filament\Resources\App\Infrastructure\Models\PricingResource\Pages;

use App\Filament\Resources\App\Infrastructure\Models\PricingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPricings extends ListRecords
{
    protected static string $resource = PricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
