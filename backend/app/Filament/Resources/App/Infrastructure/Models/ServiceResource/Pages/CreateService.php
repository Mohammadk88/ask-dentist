<?php

namespace App\Filament\Resources\App\Infrastructure\Models\ServiceResource\Pages;

use App\Filament\Resources\App\Infrastructure\Models\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;
}
