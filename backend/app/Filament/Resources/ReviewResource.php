<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\Indicator;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    protected static ?string $navigationLabel = 'Reviews';
    
    protected static ?string $modelLabel = 'Review';
    
    protected static ?string $pluralModelLabel = 'Reviews';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Review Details')
                    ->description('Review information (read-only)')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('reviewable_type')
                                    ->label('Review Type')
                                    ->options([
                                        'App\\Models\\Doctor' => 'Doctor',
                                        'App\\Models\\Clinic' => 'Clinic',
                                    ])
                                    ->disabled()
                                    ->dehydrated(false),

                                Select::make('reviewable_id')
                                    ->label('Reviewed Entity')
                                    ->options(function (callable $get) {
                                        $type = $get('reviewable_type');
                                        if ($type === 'App\\Models\\Doctor') {
                                            return \App\Models\Doctor::pluck('name', 'id');
                                        } elseif ($type === 'App\\Models\\Clinic') {
                                            return \App\Models\Clinic::pluck('name', 'id');
                                        }
                                        return [];
                                    })
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('user.name')
                                    ->label('Reviewer')
                                    ->disabled()
                                    ->dehydrated(false),

                                TextInput::make('rating')
                                    ->label('Rating')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->suffix(' stars')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),

                        Textarea::make('comment')
                            ->label('Review Comment')
                            ->rows(4)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('created_at')
                                    ->label('Submitted At')
                                    ->disabled()
                                    ->dehydrated(false),

                                TextInput::make('updated_at')
                                    ->label('Updated At')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->disabled(); // Make entire form read-only
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reviewable_type')
                    ->label('Type')
                    ->getStateUsing(fn ($record) => class_basename($record->reviewable_type))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Doctor' => 'success',
                        'Clinic' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('reviewable.name')
                    ->label('Reviewed Entity')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('Reviewer')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->getStateUsing(fn ($record) => str_repeat('⭐', $record->rating) . ' (' . $record->rating . '/5)')
                    ->sortable(),

                TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->wrap(),

                TextColumn::make('rating_context')
                    ->label('Rating Summary')
                    ->getStateUsing(function ($record) {
                        if ($record->reviewable) {
                            $avgRating = $record->reviewable->reviews()->avg('rating');
                            $totalReviews = $record->reviewable->reviews()->count();
                            return $avgRating ? number_format($avgRating, 1) . ' avg (' . $totalReviews . ' reviews)' : 'No avg yet';
                        }
                        return 'N/A';
                    })
                    ->badge()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('reviewable_type')
                    ->label('Review Type')
                    ->options([
                        'App\\Models\\Doctor' => 'Doctor Reviews',
                        'App\\Models\\Clinic' => 'Clinic Reviews',
                    ]),

                SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        '5' => '5 Stars',
                        '4' => '4 Stars',
                        '3' => '3 Stars',
                        '2' => '2 Stars',
                        '1' => '1 Star',
                    ])
                    ->multiple(),

                Filter::make('recent')
                    ->label('Recent Reviews')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7)))
                    ->indicator('Recent (7 days)'),

                Filter::make('high_rated')
                    ->label('High Rated (4+ stars)')
                    ->query(fn (Builder $query): Builder => $query->where('rating', '>=', 4))
                    ->indicator('High Rated'),

                Filter::make('low_rated')
                    ->label('Low Rated (2 or less)')
                    ->query(fn (Builder $query): Builder => $query->where('rating', '<=', 2))
                    ->indicator('Low Rated'),
            ])
            ->actions([
                // Read-only resource - view only
            ])
            ->bulkActions([
                // Removed bulk actions to prevent accidental modifications
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            // Read-only resource - no create/edit pages
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Disable creating new reviews from admin
    }

    public static function canEdit($record): bool
    {
        return false; // Disable editing reviews
    }

    public static function canDelete($record): bool
    {
        return false; // Disable deleting reviews
    }

    public static function canDeleteAny(): bool
    {
        return false; // Disable bulk delete
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'reviewable']);
    }
}
