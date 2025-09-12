<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleToggleResource\Pages;
use App\Models\ModuleToggle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ModuleToggleResource extends Resource
{
    protected static ?string $model = ModuleToggle::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System Configuration';

    protected static ?string $navigationLabel = 'Feature Toggles';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Module Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g., stories, swiping, travel_booking')
                            ->helperText('Unique identifier for the module feature'),
                        Forms\Components\Toggle::make('enabled')
                            ->default(false)
                            ->helperText('Enable or disable this feature'),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(500)
                            ->rows(3)
                            ->placeholder('Describe what this module does...')
                            ->helperText('Brief description of the module functionality'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Module Key')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\IconColumn::make('enabled')
                    ->boolean()
                    ->sortable()
                    ->size('lg'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('enabled')
                    ->label('Status')
                    ->placeholder('All modules')
                    ->trueLabel('Enabled')
                    ->falseLabel('Disabled'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle')
                    ->label(fn ($record) => $record->enabled ? 'Disable' : 'Enable')
                    ->icon(fn ($record) => $record->enabled ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->enabled ? 'danger' : 'success')
                    ->action(function ($record) {
                        $record->enabled = !$record->enabled;
                        $record->save();
                        
                        $status = $record->enabled ? 'enabled' : 'disabled';
                        Notification::make()
                            ->title("Module {$record->key} {$status}")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => 'Toggle ' . $record->key . ' module')
                    ->modalDescription(fn ($record) => 
                        $record->enabled 
                            ? 'Are you sure you want to disable this module? Users will lose access to this feature.'
                            : 'Are you sure you want to enable this module? This feature will become available to users.'
                    ),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('enable_all')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->enabled = true;
                                $record->save();
                            });
                            Notification::make()
                                ->title('Selected modules enabled')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('disable_all')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->enabled = false;
                                $record->save();
                            });
                            Notification::make()
                                ->title('Selected modules disabled')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::enabled()->count() . '/' . static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $total = static::getModel()::count();
        $enabled = static::getModel()::enabled()->count();
        
        if ($total === 0) return 'gray';
        if ($enabled === $total) return 'success';
        if ($enabled === 0) return 'danger';
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModuleToggles::route('/'),
            'create' => Pages\CreateModuleToggle::route('/create'),
            'edit' => Pages\EditModuleToggle::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['key', 'description'];
    }
}
