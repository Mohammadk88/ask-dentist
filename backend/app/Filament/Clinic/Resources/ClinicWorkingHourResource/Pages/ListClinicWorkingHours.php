<?php

namespace App\Filament\Clinic\Resources\ClinicWorkingHourResource\Pages;

use App\Filament\Clinic\Resources\ClinicWorkingHourResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClinicWorkingHours extends ListRecords
{
    protected static string $resource = ClinicWorkingHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
