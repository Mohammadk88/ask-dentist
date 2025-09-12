<?php

namespace App\Filament\Clinic\Resources\ClinicWorkingHourResource\Pages;

use App\Filament\Clinic\Resources\ClinicWorkingHourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClinicWorkingHour extends EditRecord
{
    protected static string $resource = ClinicWorkingHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
