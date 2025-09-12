<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\ClinicWorkingHourResource\Pages;
use App\Models\ClinicWorkingHour;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;

class ClinicWorkingHourResource extends Resource
{
    protected static ?string $model = ClinicWorkingHour::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Working Hours';

    protected static ?string $modelLabel = 'Working Hours';

    protected static ?string $pluralModelLabel = 'Working Hours';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('clinic_id')
                    ->label('Clinic')
                    ->relationship('clinic', 'name')
                    ->required(),

                Forms\Components\Select::make('day_of_week')
                    ->options([
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday',
                    ])
                    ->required()
                    ->unique(ignoreDuplicates: true, ignoreRecord: true)
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_closed')
                    ->label('Closed on this day')
                    ->reactive()
                    ->columnSpanFull(),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TimePicker::make('opening_time')
                            ->label('Opening Time')
                            ->required()
                            ->hidden(fn (Forms\Get $get) => $get('is_closed')),

                        Forms\Components\TimePicker::make('closing_time')
                            ->label('Closing Time')
                            ->required()
                            ->hidden(fn (Forms\Get $get) => $get('is_closed')),
                    ]),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TimePicker::make('break_start')
                            ->label('Break Start')
                            ->hidden(fn (Forms\Get $get) => $get('is_closed')),

                        Forms\Components\TimePicker::make('break_end')
                            ->label('Break End')
                            ->hidden(fn (Forms\Get $get) => $get('is_closed')),
                    ]),

                Forms\Components\Textarea::make('notes')
                    ->label('Additional Notes')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day_display')
                    ->label('Day')
                    ->weight(FontWeight::SemiBold)
                    ->sortable('day_of_week'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (ClinicWorkingHour $record) => $record->is_closed ? 'Closed' : 'Open')
                    ->badge()
                    ->color(fn (ClinicWorkingHour $record) => $record->is_closed ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('formatted_hours')
                    ->label('Hours')
                    ->description(fn (ClinicWorkingHour $record) => $record->notes),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('day_of_week', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_closed')
                    ->label('Closed Days')
                    ->placeholder('All Days')
                    ->trueLabel('Closed Only')
                    ->falseLabel('Open Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-clock')
            ->emptyStateHeading('No working hours configured')
            ->emptyStateDescription('Set up your clinic\'s working hours for each day of the week.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Working Hours'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // For now, show all working hours. 
        // TODO: Filter by user's clinic when user-clinic relationship is established
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinicWorkingHours::route('/'),
            'create' => Pages\CreateClinicWorkingHour::route('/create'),
            'edit' => Pages\EditClinicWorkingHour::route('/{record}/edit'),
        ];
    }
}
