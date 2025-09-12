<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewQuestionResource\Pages;
use App\Models\ReviewQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ReviewQuestionResource extends Resource
{
    protected static ?string $model = ReviewQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Review Management';

    protected static ?string $navigationLabel = 'Question Bank';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Question Details')
                    ->schema([
                        Forms\Components\Textarea::make('question_text')
                            ->label('Question')
                            ->required()
                            ->maxLength(500)
                            ->rows(3)
                            ->placeholder('e.g., How would you rate the communication with your doctor?'),
                        Forms\Components\Select::make('question_type')
                            ->options([
                                'rating' => 'Rating (1-5 stars)',
                                'text' => 'Text Response',
                                'boolean' => 'Yes/No',
                                'multiple_choice' => 'Multiple Choice',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Clear options if not multiple choice
                                if ($state !== 'multiple_choice') {
                                    $set('options', null);
                                }
                            }),
                        Forms\Components\Select::make('category')
                            ->options([
                                'communication' => 'Communication',
                                'treatment' => 'Treatment Quality',
                                'facility' => 'Facility & Environment',
                                'overall' => 'Overall Experience',
                                'booking' => 'Booking Process',
                                'support' => 'Customer Support',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Question Options')
                    ->schema([
                        Forms\Components\KeyValue::make('options')
                            ->label('Multiple Choice Options')
                            ->keyLabel('Option Value')
                            ->valueLabel('Option Text')
                            ->visible(fn (Forms\Get $get) => $get('question_type') === 'multiple_choice')
                            ->required(fn (Forms\Get $get) => $get('question_type') === 'multiple_choice'),
                    ]),

                Forms\Components\Section::make('Scoring & Weights')
                    ->schema([
                        Forms\Components\KeyValue::make('weights')
                            ->label('Scoring Weights')
                            ->keyLabel('Response Value')
                            ->valueLabel('Weight/Score')
                            ->helperText('Define how different responses are weighted for overall scoring')
                            ->default([
                                '1' => '1.0',
                                '2' => '2.0',
                                '3' => '3.0',
                                '4' => '4.0',
                                '5' => '5.0',
                            ]),
                    ]),

                Forms\Components\Section::make('Question Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_required')
                            ->label('Required Question')
                            ->default(true),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive questions will not be shown to users'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->limit(60)
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('question_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'rating',
                        'success' => 'boolean',
                        'info' => 'text',
                        'warning' => 'multiple_choice',
                    ]),
                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'purple' => 'communication',
                        'blue' => 'treatment',
                        'green' => 'facility',
                        'orange' => 'overall',
                        'pink' => 'booking',
                        'gray' => 'support',
                    ]),
                Tables\Columns\IconColumn::make('is_required')
                    ->label('Required')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('question_type')
                    ->options([
                        'rating' => 'Rating',
                        'text' => 'Text',
                        'boolean' => 'Yes/No',
                        'multiple_choice' => 'Multiple Choice',
                    ]),
                Tables\Filters\SelectFilter::make('category'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
                Tables\Filters\TernaryFilter::make('is_required')
                    ->label('Required'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn ($record) => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                    ->action(function ($record) {
                        $record->is_active = !$record->is_active;
                        $record->save();
                        
                        $status = $record->is_active ? 'activated' : 'deactivated';
                        Notification::make()
                            ->title("Question {$status}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->is_active = true;
                                $record->save();
                            });
                        }),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->is_active = false;
                                $record->save();
                            });
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count() . '/' . static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviewQuestions::route('/'),
            'create' => Pages\CreateReviewQuestion::route('/create'),
            'edit' => Pages\EditReviewQuestion::route('/{record}/edit'),
        ];
    }
}
