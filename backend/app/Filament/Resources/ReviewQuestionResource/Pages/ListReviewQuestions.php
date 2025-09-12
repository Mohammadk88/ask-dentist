<?php

namespace App\Filament\Resources\ReviewQuestionResource\Pages;

use App\Filament\Resources\ReviewQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReviewQuestions extends ListRecords
{
    protected static string $resource = ReviewQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
