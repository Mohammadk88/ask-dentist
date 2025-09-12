<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeforeAfterCaseResource\Pages;
use App\Filament\Resources\BeforeAfterCaseResource\RelationManagers;
use App\Models\BeforeAfterCase;
use App\Models\Doctor;
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
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Support\Enums\FontWeight;

class BeforeAfterCaseResource extends Resource
{
    protected static ?string $model = BeforeAfterCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?string $navigationLabel = 'Before & After Cases';
    
    protected static ?string $modelLabel = 'Before & After Case';
    
    protected static ?string $pluralModelLabel = 'Before & After Cases';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Case Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('doctor_id')
                                    ->label('Doctor')
                                    ->relationship('doctor', 'id')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->name ?? 'Unknown Doctor')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('clinic_id')
                                    ->label('Clinic')
                                    ->relationship('clinic', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),

                        TextInput::make('title')
                            ->label('Case Title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Images')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('before_path')
                                    ->label('Before Image')
                                    ->directory('media/before_after')
                                    ->disk('private')
                                    ->image()
                                    ->maxSize(10240) // 10MB
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->required()
                                    ->imagePreviewHeight('200')
                                    ->helperText('Upload the before treatment image'),

                                FileUpload::make('after_path')
                                    ->label('After Image')
                                    ->directory('media/before_after')
                                    ->disk('private')
                                    ->image()
                                    ->maxSize(10240) // 10MB
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->required()
                                    ->imagePreviewHeight('200')
                                    ->helperText('Upload the after treatment image'),
                            ]),
                    ]),

                Section::make('Treatment Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('treatment_type')
                                    ->label('Treatment Type')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Teeth Whitening, Orthodontics'),

                                TextInput::make('duration_days')
                                    ->label('Treatment Duration (Days)')
                                    ->numeric()
                                    ->minValue(1),
                            ]),

                        Textarea::make('procedure_details')
                            ->label('Procedure Details')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        TextInput::make('cost_range')
                            ->label('Cost Range')
                            ->maxLength(255)
                            ->placeholder('e.g., $500 - $1,500')
                            ->columnSpanFull(),

                        TagsInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Add tags...')
                            ->helperText('Press Enter to add each tag')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Visibility')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Toggle::make('is_approved')
                                    ->label('Approved')
                                    ->helperText('Case approved for public display'),

                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->helperText('Show as featured case'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('before_path')
                        ->label('Before')
                        ->disk('private')
                        ->size(80)
                        ->square()
                        ->grow(false),

                    ImageColumn::make('after_path')
                        ->label('After')
                        ->disk('private')
                        ->size(80)
                        ->square()
                        ->grow(false),

                    TextColumn::make('title')
                        ->label('Case Title')
                        ->searchable()
                        ->weight(FontWeight::SemiBold)
                        ->grow(),
                ]),

                Panel::make([
                    Split::make([
                        TextColumn::make('doctor.user.name')
                            ->label('Doctor')
                            ->icon('heroicon-m-user')
                            ->default('No Doctor')
                            ->grow(false),

                        TextColumn::make('clinic.name')
                            ->label('Clinic')
                            ->icon('heroicon-m-building-office')
                            ->default('No Clinic')
                            ->grow(false),

                        TextColumn::make('treatment_type')
                            ->label('Treatment')
                            ->badge()
                            ->grow(false),
                    ]),
                ])->collapsible(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    }),

                BooleanColumn::make('is_approved')
                    ->label('Approved')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                BooleanColumn::make('is_featured')
                    ->label('Featured')
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star'),

                TextColumn::make('duration_days')
                    ->label('Duration')
                    ->suffix(' days')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('doctor')
                    ->relationship('doctor', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->name ?? 'Unknown Doctor')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('clinic')
                    ->relationship('clinic', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),

                TernaryFilter::make('is_approved')
                    ->label('Approved')
                    ->placeholder('All cases')
                    ->trueLabel('Approved only')
                    ->falseLabel('Pending approval'),

                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All cases')
                    ->trueLabel('Featured only')
                    ->falseLabel('Regular cases'),
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
            'index' => Pages\ListBeforeAfterCases::route('/'),
            'create' => Pages\CreateBeforeAfterCase::route('/create'),
            'edit' => Pages\EditBeforeAfterCase::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['doctor.user', 'clinic']);
    }
}
