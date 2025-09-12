<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $modelLabel = 'Service';

    protected static ?string $pluralModelLabel = 'Services';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => 
                                $context === 'create' ? $set('slug', \Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),
                    ]),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(1000)
                    ->columnSpanFull(),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Select::make('category')
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
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('Duration (minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        Forms\Components\TextInput::make('formatted_duration')
                            ->label('Duration Display')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\Toggle::make('requires_anesthesia')
                            ->label('Requires Anesthesia'),

                        Forms\Components\Toggle::make('requires_followup')
                            ->label('Requires Follow-up'),

                        Forms\Components\Toggle::make('is_emergency')
                            ->label('Emergency Service'),

                        Forms\Components\Toggle::make('is_tooth_specific')
                            ->label('Tooth Specific'),
                    ]),

                Forms\Components\Repeater::make('prerequisites')
                    ->label('Prerequisites')
                    ->schema([
                        Forms\Components\TextInput::make('requirement')
                            ->label('Requirement')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description'),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category_display')
                    ->label('Category')
                    ->badge()
                    ->color(fn (Service $record) => match($record->category) {
                        'emergency' => 'danger',
                        'surgical' => 'warning',
                        'cosmetic' => 'success',
                        'preventive' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('formatted_duration')
                    ->label('Duration')
                    ->sortable('duration_minutes'),

                Tables\Columns\IconColumn::make('requires_anesthesia')
                    ->label('Anesthesia')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('requires_followup')
                    ->label('Follow-up')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_emergency')
                    ->label('Emergency')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_tooth_specific')
                    ->label('Tooth Specific')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pricing_count')
                    ->label('Price Points')
                    ->counts('pricing')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
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

                Tables\Filters\TernaryFilter::make('is_emergency')
                    ->label('Emergency Services')
                    ->placeholder('All Services')
                    ->trueLabel('Emergency Only')
                    ->falseLabel('Non-Emergency Only'),

                Tables\Filters\TernaryFilter::make('requires_anesthesia')
                    ->label('Anesthesia Required')
                    ->placeholder('All Services')
                    ->trueLabel('Requires Anesthesia')
                    ->falseLabel('No Anesthesia'),

                Tables\Filters\TernaryFilter::make('requires_followup')
                    ->label('Follow-up Required')
                    ->placeholder('All Services')
                    ->trueLabel('Requires Follow-up')
                    ->falseLabel('No Follow-up'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->emptyStateHeading('No services defined')
            ->emptyStateDescription('Define the dental services your clinic provides.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Service'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
