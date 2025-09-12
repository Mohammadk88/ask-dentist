<?php

namespace App\Filament\Resources\App\Infrastructure\Models\ServiceResource\Pages;

use App\Filament\Resources\App\Infrastructure\Models\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
