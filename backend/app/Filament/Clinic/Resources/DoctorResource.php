<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Doctors';

    protected static ?string $modelLabel = 'Doctor';

    protected static ?string $pluralModelLabel = 'Doctors';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User Account')
                    ->relationship('user', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique('users', 'email'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Hidden::make('role')
                            ->default('doctor'),
                        Forms\Components\Hidden::make('status')
                            ->default('active'),
                    ]),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('license_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('specialty')
                            ->options([
                                'general' => 'General Dentistry',
                                'orthodontics' => 'Orthodontics',
                                'oral_surgery' => 'Oral Surgery',
                                'endodontics' => 'Endodontics',
                                'periodontics' => 'Periodontics',
                                'prosthodontics' => 'Prosthodontics',
                                'pediatric' => 'Pediatric Dentistry',
                                'cosmetic' => 'Cosmetic Dentistry',
                                'implantology' => 'Implantology',
                            ])
                            ->required(),
                    ]),

                Forms\Components\Textarea::make('bio')
                    ->label('Biography')
                    ->maxLength(1000)
                    ->columnSpanFull(),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('years_experience')
                            ->label('Years of Experience')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),

                        Forms\Components\TagsInput::make('languages')
                            ->label('Languages Spoken')
                            ->placeholder('Add languages...')
                            ->suggestions(['English', 'Turkish', 'Arabic', 'German', 'French', 'Russian']),
                    ]),

                Forms\Components\Repeater::make('qualifications')
                    ->label('Qualifications & Certifications')
                    ->schema([
                        Forms\Components\TextInput::make('institution')
                            ->label('Institution')
                            ->required(),
                        Forms\Components\TextInput::make('degree')
                            ->label('Degree/Certificate')
                            ->required(),
                        Forms\Components\TextInput::make('year')
                            ->label('Year')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                Forms\Components\Toggle::make('accepts_emergency')
                    ->label('Accepts Emergency Cases')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('specialty')
                    ->label('Specialty')
                    ->formatStateUsing(fn (string $state) => match($state) {
                        'general' => 'General Dentistry',
                        'orthodontics' => 'Orthodontics',
                        'oral_surgery' => 'Oral Surgery',
                        'endodontics' => 'Endodontics',
                        'periodontics' => 'Periodontics',
                        'prosthodontics' => 'Prosthodontics',
                        'pediatric' => 'Pediatric Dentistry',
                        'cosmetic' => 'Cosmetic Dentistry',
                        'implantology' => 'Implantology',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('license_number')
                    ->label('License')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('years_experience')
                    ->label('Experience')
                    ->suffix(' years')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . '/5.0' : 'N/A')
                    ->sortable(),

                Tables\Columns\IconColumn::make('verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('accepts_emergency')
                    ->label('Emergency')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('user.name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('specialty')
                    ->options([
                        'general' => 'General Dentistry',
                        'orthodontics' => 'Orthodontics',
                        'oral_surgery' => 'Oral Surgery',
                        'endodontics' => 'Endodontics',
                        'periodontics' => 'Periodontics',
                        'prosthodontics' => 'Prosthodontics',
                        'pediatric' => 'Pediatric Dentistry',
                        'cosmetic' => 'Cosmetic Dentistry',
                        'implantology' => 'Implantology',
                    ]),

                Tables\Filters\TernaryFilter::make('verified_at')
                    ->label('Verification Status')
                    ->placeholder('All Doctors')
                    ->trueLabel('Verified Only')
                    ->falseLabel('Unverified Only'),

                Tables\Filters\TernaryFilter::make('accepts_emergency')
                    ->label('Emergency Availability')
                    ->placeholder('All Doctors')
                    ->trueLabel('Accepts Emergency')
                    ->falseLabel('No Emergency'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateHeading('No doctors assigned')
            ->emptyStateDescription('Add doctors to your clinic to start managing their profiles.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Doctor'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // For clinic managers, scope to doctors at their clinics
        if (auth()->user()?->role === 'clinic_manager') {
            // Get the first clinic for now - in production this should be properly linked
            $clinic = \App\Models\Clinic::first();
            if ($clinic) {
                $query->whereHas('clinics', function (Builder $query) use ($clinic) {
                    $query->where('clinic_id', $clinic->id);
                });
            }
        }
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
