<?php

namespace App\Filament\Resources\BeforeAfterCaseResource\Pages;

use App\Filament\Resources\BeforeAfterCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeforeAfterCase extends EditRecord
{
    protected static string $resource = BeforeAfterCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
