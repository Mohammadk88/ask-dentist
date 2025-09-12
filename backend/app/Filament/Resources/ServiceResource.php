<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\BooleanEntry;
use Filament\Infolists\Infolist;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Service Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => $context === 'create' ? $set('slug', \Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Service::class, 'slug', ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3),

                        Forms\Components\Select::make('category')
                            ->required()
                            ->options([
                                'general' => 'General Dentistry',
                                'preventive' => 'Preventive Care',
                                'restorative' => 'Restorative',
                                'cosmetic' => 'Cosmetic',
                                'orthodontic' => 'Orthodontic',
                                'surgical' => 'Oral Surgery',
                                'endodontic' => 'Root Canal',
                                'periodontal' => 'Gum Treatment',
                                'pediatric' => 'Pediatric',
                                'emergency' => 'Emergency',
                            ]),

                        Forms\Components\TextInput::make('duration_minutes')
                            ->required()
                            ->numeric()
                            ->suffix('minutes')
                            ->minValue(1)
                            ->maxValue(480),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Service Flags')
                    ->schema([
                        Forms\Components\Toggle::make('requires_anesthesia')
                            ->label('Requires Anesthesia'),

                        Forms\Components\Toggle::make('requires_followup')
                            ->label('Requires Follow-up'),

                        Forms\Components\Toggle::make('is_emergency')
                            ->label('Emergency Service'),

                        Forms\Components\Toggle::make('is_tooth_specific')
                            ->label('Tooth Specific'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Prerequisites')
                    ->schema([
                        Forms\Components\TagsInput::make('prerequisites')
                            ->placeholder('Add prerequisites (optional)')
                            ->helperText('List any requirements or preparations needed before this service.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'general' => 'General',
                        'preventive' => 'Preventive',
                        'restorative' => 'Restorative',
                        'cosmetic' => 'Cosmetic',
                        'orthodontic' => 'Orthodontic',
                        'surgical' => 'Surgery',
                        'endodontic' => 'Root Canal',
                        'periodontal' => 'Gum Care',
                        'pediatric' => 'Pediatric',
                        'emergency' => 'Emergency',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match($state) {
                        'emergency' => 'danger',
                        'surgical' => 'warning',
                        'cosmetic' => 'info',
                        'preventive' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('formatted_duration')
                    ->label('Duration')
                    ->getStateUsing(function (Service $record): string {
                        $hours = floor($record->duration_minutes / 60);
                        $minutes = $record->duration_minutes % 60;
                        if ($hours > 0) {
                            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
                        }
                        return "{$minutes}m";
                    })
                    ->sortable('duration_minutes'),

                Tables\Columns\IconColumn::make('requires_anesthesia')
                    ->label('Anesthesia')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\IconColumn::make('is_emergency')
                    ->label('Emergency')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\TextColumn::make('pricing_count')
                    ->label('Pricing Options')
                    ->counts('pricing')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'general' => 'General Dentistry',
                        'preventive' => 'Preventive Care',
                        'restorative' => 'Restorative',
                        'cosmetic' => 'Cosmetic',
                        'orthodontic' => 'Orthodontic',
                        'surgical' => 'Oral Surgery',
                        'endodontic' => 'Root Canal',
                        'periodontal' => 'Gum Treatment',
                        'pediatric' => 'Pediatric',
                        'emergency' => 'Emergency',
                    ]),

                Tables\Filters\TernaryFilter::make('requires_anesthesia')
                    ->label('Requires Anesthesia'),

                Tables\Filters\TernaryFilter::make('is_emergency')
                    ->label('Emergency Service'),

                Tables\Filters\Filter::make('duration')
                    ->form([
                        Forms\Components\TextInput::make('duration_from')
                            ->numeric()
                            ->suffix('minutes'),
                        Forms\Components\TextInput::make('duration_to')
                            ->numeric()
                            ->suffix('minutes'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['duration_from'],
                                fn ($query, $duration) => $query->where('duration_minutes', '>=', $duration),
                            )
                            ->when(
                                $data['duration_to'],
                                fn ($query, $duration) => $query->where('duration_minutes', '<=', $duration),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Service Information')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('slug')
                            ->copyable(),
                        TextEntry::make('category_display')
                            ->label('Category'),
                        TextEntry::make('description')
                            ->columnSpanFull(),
                        TextEntry::make('formatted_duration')
                            ->label('Duration'),
                    ])
                    ->columns(2),

                \Filament\Infolists\Components\Section::make('Service Flags')
                    ->schema([
                        BooleanEntry::make('requires_anesthesia')
                            ->label('Requires Anesthesia'),
                        BooleanEntry::make('requires_followup')
                            ->label('Requires Follow-up'),
                        BooleanEntry::make('is_emergency')
                            ->label('Emergency Service'),
                        BooleanEntry::make('is_tooth_specific')
                            ->label('Tooth Specific'),
                    ])
                    ->columns(2),

                \Filament\Infolists\Components\Section::make('Prerequisites')
                    ->schema([
                        TextEntry::make('prerequisites')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->limitList(10)
                            ->expandableLimitedList()
                            ->placeholder('No prerequisites'),
                    ])
                    ->hidden(fn (Service $record): bool => empty($record->prerequisites)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
