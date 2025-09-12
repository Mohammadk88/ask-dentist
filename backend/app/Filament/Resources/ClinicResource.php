<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicResource\Pages;
use App\Filament\Resources\ClinicResource\RelationManagers;
use App\Models\Clinic;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Support\Enums\FontWeight;

class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationLabel = 'Clinics';
    
    protected static ?string $modelLabel = 'Clinic';
    
    protected static ?string $pluralModelLabel = 'Clinics';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Clinic Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(20),

                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->maxLength(255),
                            ]),

                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://')
                            ->columnSpanFull(),
                    ]),

                Section::make('Location')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('country')
                                    ->label('Country')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('city')
                                    ->label('City')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        TextInput::make('address')
                            ->label('Full Address')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.000001)
                                    ->helperText('GPS coordinate for map display'),

                                TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.000001)
                                    ->helperText('GPS coordinate for map display'),
                            ]),
                    ]),

                Section::make('Operating Hours')
                    ->schema([
                        Repeater::make('operating_hours')
                            ->label('Operating Hours')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('day')
                                            ->label('Day')
                                            ->options([
                                                'monday' => 'Monday',
                                                'tuesday' => 'Tuesday',
                                                'wednesday' => 'Wednesday',
                                                'thursday' => 'Thursday',
                                                'friday' => 'Friday',
                                                'saturday' => 'Saturday',
                                                'sunday' => 'Sunday',
                                            ])
                                            ->required(),

                                        TextInput::make('open')
                                            ->label('Open Time')
                                            ->placeholder('09:00')
                                            ->helperText('24-hour format (HH:MM)'),

                                        TextInput::make('close')
                                            ->label('Close Time')
                                            ->placeholder('17:00')
                                            ->helperText('24-hour format (HH:MM)'),
                                    ]),

                                Toggle::make('is_closed')
                                    ->label('Closed on this day'),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('cover_path')
                            ->label('Cover Image')
                            ->directory('media/covers')
                            ->disk('public')
                            ->image()
                            ->maxSize(10240) // 10MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->imagePreviewHeight('200')
                            ->helperText('Cover photo for clinic profile (recommended: 16:9 aspect ratio)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Verification & Promotion')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('verified_at')
                                    ->label('Verified At')
                                    ->helperText('When the clinic was verified'),

                                TextInput::make('commission_rate')
                                    ->label('Commission Rate (%)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_promoted')
                                    ->label('Promoted')
                                    ->reactive()
                                    ->helperText('Show as promoted clinic'),

                                DateTimePicker::make('promoted_until')
                                    ->label('Promoted Until')
                                    ->helperText('Promotion end date and time')
                                    ->visible(fn (callable $get) => $get('is_promoted'))
                                    ->required(fn (callable $get) => $get('is_promoted')),
                            ]),

                        Textarea::make('verification_notes')
                            ->label('Verification Notes')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Internal notes about verification process')
                            ->columnSpanFull(),

                        Textarea::make('documents_json')
                            ->label('Documents')
                            ->rows(3)
                            ->helperText('JSON data for uploaded documents')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_path')
                    ->label('Cover')
                    ->disk('public')
                    ->size(60)
                    ->square(),

                TextColumn::make('name')
                    ->label('Clinic Name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold),

                TextColumn::make('city')
                    ->label('Location')
                    ->getStateUsing(fn ($record) => "{$record->city}, {$record->country}")
                    ->searchable(['city', 'country'])
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BooleanColumn::make('verified_at')
                    ->label('Verified')
                    ->getStateUsing(fn ($record) => !is_null($record->verified_at))
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-clock'),

                TextColumn::make('commission_rate')
                    ->label('Commission')
                    ->suffix('%')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('promotion_status')
                    ->label('Promotion')
                    ->getStateUsing(function ($record) {
                        if (!$record->is_promoted) {
                            return 'Not Promoted';
                        }
                        if ($record->promoted_until && $record->promoted_until < now()) {
                            return 'Expired';
                        }
                        return 'Active';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Expired' => 'warning',
                        'Not Promoted' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('promoted_until')
                    ->label('Promoted Until')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('doctors_count')
                    ->label('Doctors')
                    ->counts('doctors')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('country')
                    ->options(function () {
                        return Clinic::distinct('country')
                            ->whereNotNull('country')
                            ->pluck('country', 'country')
                            ->toArray();
                    })
                    ->searchable(),

                SelectFilter::make('city')
                    ->options(function () {
                        return Clinic::distinct('city')
                            ->whereNotNull('city')
                            ->pluck('city', 'city')
                            ->toArray();
                    })
                    ->searchable(),

                TernaryFilter::make('verified_at')
                    ->label('Verified')
                    ->placeholder('All clinics')
                    ->trueLabel('Verified only')
                    ->falseLabel('Unverified only')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('verified_at'),
                        false: fn (Builder $query) => $query->whereNull('verified_at'),
                    ),

                SelectFilter::make('promotion_status')
                    ->label('Promotion Status')
                    ->options([
                        'active' => 'Active Promotion',
                        'expired' => 'Expired Promotion', 
                        'none' => 'Not Promoted',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'active',
                            fn (Builder $query): Builder => $query->where('is_promoted', true)
                                ->where(function ($q) {
                                    $q->whereNull('promoted_until')
                                      ->orWhere('promoted_until', '>', now());
                                }),
                        )->when(
                            $data['value'] === 'expired',
                            fn (Builder $query): Builder => $query->where('is_promoted', true)
                                ->whereNotNull('promoted_until')
                                ->where('promoted_until', '<=', now()),
                        )->when(
                            $data['value'] === 'none',
                            fn (Builder $query): Builder => $query->where('is_promoted', false),
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('togglePromotion')
                    ->label('Toggle Promotion')
                    ->icon('heroicon-o-megaphone')
                    ->color('warning')
                    ->action(function (Clinic $record) {
                        $record->update([
                            'is_promoted' => !$record->is_promoted,
                            'promoted_until' => $record->is_promoted ? null : now()->addDays(30),
                        ]);
                    })
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListClinics::route('/'),
            'create' => Pages\CreateClinic::route('/create'),
            'edit' => Pages\EditClinic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount(['doctors']);
    }
}
