<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Filament\Resources\StoryResource\RelationManagers;
use App\Models\Story;
use App\Models\Doctor;
use App\Models\Clinic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Support\Enums\FontWeight;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';

    protected static ?string $navigationLabel = 'Stories';

    protected static ?string $modelLabel = 'Story';

    protected static ?string $pluralModelLabel = 'Stories';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        // Owner Type Selection
                        Select::make('owner_type')
                            ->label('Owner Type')
                            ->options([
                                Doctor::class => 'Doctor',
                                Clinic::class => 'Clinic',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('owner_id', null)),

                        // Owner Selection (Dynamic based on owner_type)
                        Select::make('owner_id')
                            ->label('Owner')
                            ->required()
                            ->options(function (callable $get) {
                                $ownerType = $get('owner_type');
                                if (!$ownerType) {
                                    return [];
                                }

                                if ($ownerType === Doctor::class) {
                                    return Doctor::with('user')
                                        ->get()
                                        ->pluck('user.name', 'id')
                                        ->toArray();
                                }

                                if ($ownerType === Clinic::class) {
                                    return Clinic::pluck('name', 'id')->toArray();
                                }

                                return [];
                            })
                            ->searchable()
                            ->preload(),
                    ]),

                // Caption
                Textarea::make('caption')
                    ->label('Caption')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([
                        // Language
                        Select::make('lang')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                                'es' => 'Spanish',
                                'fr' => 'French',
                            ])
                            ->default('en')
                            ->required(),

                        // Is Advertisement
                        Toggle::make('is_ad')
                            ->label('Advertisement')
                            ->helperText('Mark as sponsored/promotional content'),
                    ]),

                // Media Upload (Multiple files)
                Repeater::make('media')
                    ->label('Media Files')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('path')
                                    ->label('Media File')
                                    ->directory('media/stories')
                                    ->disk('public')
                                    ->image()
                                    ->video()
                                    ->acceptedFileTypes(['image/*', 'video/mp4', 'video/quicktime'])
                                    ->maxSize(20480) // 20MB
                                    ->required(),

                                Select::make('type')
                                    ->label('Media Type')
                                    ->options([
                                        'image' => 'Image',
                                        'video' => 'Video',
                                    ])
                                    ->default('image')
                                    ->required(),
                            ]),

                        TextInput::make('caption')
                            ->label('Media Caption')
                            ->maxLength(255),
                    ])
                    ->defaultItems(1)
                    ->reorderable()
                    ->collapsible()
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([
                        // Start Date
                        DateTimePicker::make('starts_at')
                            ->label('Start Date & Time')
                            ->default(now())
                            ->required()
                            ->helperText('When the story becomes visible'),

                        // Expiry Date
                        DateTimePicker::make('expires_at')
                            ->label('Expires Date & Time')
                            ->default(now()->addHours(24))
                            ->required()
                            ->after('starts_at')
                            ->helperText('When the story automatically expires'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('media')
                    ->label('Media')
                    ->getStateUsing(function ($record) {
                        $media = is_array($record->media) ? $record->media : json_decode($record->media, true);
                        return $media[0]['path'] ?? null;
                    })
                    ->disk('public')
                    ->size(60)
                    ->circular(),

                TextColumn::make('caption')
                    ->label('Caption')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                TextColumn::make('owner')
                    ->label('Owner')
                    ->getStateUsing(function ($record) {
                        if (!$record->owner) {
                            return 'Unknown';
                        }

                        if ($record->owner instanceof Doctor) {
                            return $record->owner->user?->name ?? 'Unknown Doctor';
                        }

                        if ($record->owner instanceof Clinic) {
                            return $record->owner->name ?? 'Unknown Clinic';
                        }

                        return 'Unknown';
                    })
                    ->searchable(),

                TextColumn::make('owner_type')
                    ->label('Type')
                    ->getStateUsing(fn ($record) => class_basename($record->owner_type))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Doctor' => 'success',
                        'Clinic' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('lang')
                    ->label('Language')
                    ->badge(),

                BooleanColumn::make('is_ad')
                    ->label('Ad')
                    ->trueIcon('heroicon-o-megaphone')
                    ->falseIcon('heroicon-o-user'),

                TextColumn::make('starts_at')
                    ->label('Starts')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at < now() ? 'danger' : 'success'),

                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if ($record->expires_at < now()) {
                            return 'Expired';
                        }
                        if ($record->starts_at > now()) {
                            return 'Scheduled';
                        }
                        return 'Active';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Scheduled' => 'warning',
                        'Expired' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('owner_type')
                    ->label('Owner Type')
                    ->options([
                        Doctor::class => 'Doctor',
                        Clinic::class => 'Clinic',
                    ]),

                SelectFilter::make('lang')
                    ->label('Language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                        'es' => 'Spanish',
                        'fr' => 'French',
                    ]),

                TernaryFilter::make('is_ad')
                    ->label('Advertisement')
                    ->placeholder('All stories')
                    ->trueLabel('Advertisements only')
                    ->falseLabel('Organic stories only'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'scheduled' => 'Scheduled',
                        'expired' => 'Expired',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'active',
                            fn (Builder $query): Builder => $query->where('starts_at', '<=', now())
                                ->where('expires_at', '>', now()),
                        )->when(
                            $data['value'] === 'scheduled',
                            fn (Builder $query): Builder => $query->where('starts_at', '>', now()),
                        )->when(
                            $data['value'] === 'expired',
                            fn (Builder $query): Builder => $query->where('expires_at', '<=', now()),
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStories::route('/'),
            'create' => Pages\CreateStory::route('/create'),
            'edit' => Pages\EditStory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['owner']);
    }
}
