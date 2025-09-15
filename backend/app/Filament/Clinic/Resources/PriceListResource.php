<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\PriceListResource\Pages;
use App\Models\Pricing;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;

class PriceListResource extends Resource
{
    protected static ?string $model = Pricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Price Lists';

    protected static ?string $modelLabel = 'Price';

    protected static ?string $pluralModelLabel = 'Price Lists';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('clinic_id')
                    ->label('Clinic')
                    ->relationship('clinic', 'name')
                    ->required(),

                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Service $record) => 
                        "{$record->name} ({$record->category_display})"
                    ),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('base_price')
                            ->label('Base Price')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->step(0.01),

                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'TRY' => 'TRY - Turkish Lira',
                                'GBP' => 'GBP - British Pound',
                                'AED' => 'AED - UAE Dirham',
                            ])
                            ->default('USD')
                            ->required(),

                        Forms\Components\TextInput::make('discount_percentage')
                            ->label('Discount %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->step(0.01),
                    ]),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('valid_from')
                            ->label('Valid From')
                            ->default(now())
                            ->required(),

                        Forms\Components\DateTimePicker::make('valid_until')
                            ->label('Valid Until')
                            ->after('valid_from'),
                    ]),

                Forms\Components\Toggle::make('is_negotiable')
                    ->label('Price is Negotiable')
                    ->columnSpanFull(),

                Forms\Components\Repeater::make('conditions')
                    ->label('Special Conditions')
                    ->schema([
                        Forms\Components\TextInput::make('condition')
                            ->label('Condition')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description'),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

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
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('service.category_display')
                    ->label('Category')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Price')
                    ->weight(FontWeight::Medium)
                    ->color(fn (Pricing $record) => $record->discount_percentage > 0 ? 'success' : null),

                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label('Discount')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable()
                    ->color(fn ($state) => $state > 0 ? 'success' : null),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'scheduled' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_negotiable')
                    ->label('Negotiable')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('valid_from')
                    ->label('Valid From')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Valid Until')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('No expiry'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('service.name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('service.category')
                    ->label('Service Category')
                    ->relationship('service', 'category')
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

                Tables\Filters\SelectFilter::make('currency')
                    ->options([
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'TRY' => 'TRY',
                        'GBP' => 'GBP',
                        'AED' => 'AED',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'scheduled' => 'Scheduled',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'active',
                            fn (Builder $query): Builder => $query->where('valid_from', '<=', now())
                                ->where(function ($q) {
                                    $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                                }),
                            fn (Builder $query): Builder => $query->when(
                                $data['value'] === 'expired',
                                fn (Builder $query): Builder => $query->where('valid_until', '<', now()),
                                fn (Builder $query): Builder => $query->when(
                                    $data['value'] === 'scheduled',
                                    fn (Builder $query): Builder => $query->where('valid_from', '>', now())
                                )
                            )
                        );
                    }),

                Tables\Filters\TernaryFilter::make('is_negotiable')
                    ->label('Negotiable Prices')
                    ->placeholder('All Prices')
                    ->trueLabel('Negotiable Only')
                    ->falseLabel('Fixed Price Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-currency-dollar')
            ->emptyStateHeading('No pricing configured')
            ->emptyStateDescription('Set up pricing for your clinic\'s services.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Pricing'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // For clinic managers, scope to pricing of their clinic
        if (auth()->user()?->role === 'clinic_manager') {
            // Get the first clinic for now - in production this should be properly linked
            $clinic = \App\Models\Clinic::first();
            if ($clinic) {
                $query->where('clinic_id', $clinic->id);
            }
        }
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPriceLists::route('/'),
            'create' => Pages\CreatePriceList::route('/create'),
            'edit' => Pages\EditPriceList::route('/{record}/edit'),
        ];
    }
}
