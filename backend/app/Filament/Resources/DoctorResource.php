<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Resources\DoctorResource\RelationManagers;
use App\Models\Doctor;
use App\Models\User;
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
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Support\Enums\FontWeight;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    
    protected static ?string $navigationLabel = 'Doctors';
    
    protected static ?string $modelLabel = 'Doctor';
    
    protected static ?string $pluralModelLabel = 'Doctors';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('password')
                                    ->password()
                                    ->required()
                                    ->minLength(8),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Professional Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('license_number')
                                    ->label('License Number')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                TextInput::make('specialty')
                                    ->label('Specialty')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., General Dentistry, Orthodontics'),
                            ]),

                        Textarea::make('bio')
                            ->label('Biography')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('years_experience')
                                    ->label('Years of Experience')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(50),

                                TextInput::make('rating')
                                    ->label('Rating')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(5)
                                    ->step(0.1)
                                    ->helperText('Average rating out of 5'),
                            ]),

                        TextInput::make('total_reviews')
                            ->label('Total Reviews')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        TagsInput::make('qualifications')
                            ->label('Qualifications')
                            ->placeholder('Add qualifications...')
                            ->helperText('Press Enter to add each qualification')
                            ->columnSpanFull(),

                        TagsInput::make('languages')
                            ->label('Languages')
                            ->placeholder('Add languages...')
                            ->helperText('Press Enter to add each language')
                            ->columnSpanFull(),

                        Toggle::make('accepts_emergency')
                            ->label('Accepts Emergency Cases')
                            ->helperText('Available for emergency consultations'),
                    ]),

                Section::make('Media')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('avatar_path')
                                    ->label('Avatar')
                                    ->directory('media/avatars')
                                    ->disk('public')
                                    ->image()
                                    ->maxSize(5120) // 5MB
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->imagePreviewHeight('150')
                                    ->helperText('Profile picture (recommended: square image)'),

                                FileUpload::make('cover_path')
                                    ->label('Cover Image')
                                    ->directory('media/covers')
                                    ->disk('public')
                                    ->image()
                                    ->maxSize(10240) // 10MB
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->imagePreviewHeight('150')
                                    ->helperText('Cover photo (recommended: 16:9 aspect ratio)'),
                            ]),
                    ]),

                Section::make('Verification & Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('verified_at')
                                    ->label('Verified At')
                                    ->helperText('When the doctor was verified'),

                                Toggle::make('is_promoted')
                                    ->label('Promoted')
                                    ->reactive()
                                    ->helperText('Show as promoted doctor'),
                            ]),

                        DateTimePicker::make('promoted_until')
                            ->label('Promoted Until')
                            ->helperText('Promotion end date and time')
                            ->visible(fn (callable $get) => $get('is_promoted'))
                            ->required(fn (callable $get) => $get('is_promoted')),

                        Textarea::make('verification_notes')
                            ->label('Verification Notes')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Internal notes about verification process')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_path')
                    ->label('Avatar')
                    ->disk('public')
                    ->size(60)
                    ->circular(),

                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold),

                TextColumn::make('specialty')
                    ->label('Specialty')
                    ->searchable()
                    ->badge(),

                TextColumn::make('license_number')
                    ->label('License')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('years_experience')
                    ->label('Experience')
                    ->suffix(' years')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric(decimalPlaces: 1)
                    ->sortable()
                    ->icon('heroicon-m-star')
                    ->color(fn ($state) => $state >= 4.5 ? 'success' : ($state >= 4.0 ? 'warning' : 'danger')),

                TextColumn::make('total_reviews')
                    ->label('Reviews')
                    ->numeric()
                    ->sortable(),

                BooleanColumn::make('verified_at')
                    ->label('Verified')
                    ->getStateUsing(fn ($record) => !is_null($record->verified_at))
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-clock'),

                BooleanColumn::make('accepts_emergency')
                    ->label('Emergency')
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-minus'),

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

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('specialty')
                    ->options(function () {
                        return Doctor::distinct('specialty')
                            ->whereNotNull('specialty')
                            ->pluck('specialty', 'specialty')
                            ->toArray();
                    })
                    ->searchable(),

                TernaryFilter::make('verified_at')
                    ->label('Verified')
                    ->placeholder('All doctors')
                    ->trueLabel('Verified only')
                    ->falseLabel('Unverified only')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('verified_at'),
                        false: fn (Builder $query) => $query->whereNull('verified_at'),
                    ),

                TernaryFilter::make('accepts_emergency')
                    ->label('Emergency Available')
                    ->placeholder('All doctors')
                    ->trueLabel('Emergency available')
                    ->falseLabel('No emergency'),

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
                    ->action(function (Doctor $record) {
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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user']);
    }
}
