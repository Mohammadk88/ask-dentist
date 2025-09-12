<?php

namespace App\Filament\Resources\ModuleToggleResource\Pages;

use App\Filament\Resources\ModuleToggleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModuleToggle extends EditRecord
{
    protected static string $resource = ModuleToggleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
