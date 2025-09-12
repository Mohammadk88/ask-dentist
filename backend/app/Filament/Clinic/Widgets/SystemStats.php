<?php

namespace App\Filament\Clinic\Widgets;

use Filament\Widgets\ChartWidget;

class SystemStats extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
