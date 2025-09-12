<?php

namespace App\Filament\Resources\ModuleToggleResource\Pages;

use App\Filament\Resources\ModuleToggleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModuleToggles extends ListRecords
{
    protected static string $resource = ModuleToggleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
