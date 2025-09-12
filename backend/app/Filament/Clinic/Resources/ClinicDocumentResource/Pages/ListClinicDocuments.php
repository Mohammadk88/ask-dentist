<?php

namespace App\Filament\Clinic\Resources\ClinicDocumentResource\Pages;

use App\Filament\Clinic\Resources\ClinicDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClinicDocuments extends ListRecords
{
    protected static string $resource = ClinicDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
