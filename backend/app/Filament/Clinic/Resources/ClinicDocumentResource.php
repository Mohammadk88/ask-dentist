<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\ClinicDocumentResource\Pages;
use App\Models\ClinicDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;

class ClinicDocumentResource extends Resource
{
    protected static ?string $model = ClinicDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documents & Licenses';

    protected static ?string $modelLabel = 'Document';

    protected static ?string $pluralModelLabel = 'Documents & Licenses';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('clinic_id')
                    ->label('Clinic')
                    ->relationship('clinic', 'name')
                    ->required(),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('document_type')
                            ->options([
                                'license' => 'Medical License',
                                'certificate' => 'Certificate',
                                'insurance' => 'Insurance Document',
                                'registration' => 'Business Registration',
                                'permit' => 'Operating Permit',
                                'other' => 'Other',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('document_name')
                            ->label('Document Name')
                            ->required()
                            ->maxLength(255),
                    ]),

                Forms\Components\FileUpload::make('file_path')
                    ->label('Document File')
                    ->directory('clinic-documents')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
                    ->maxSize(10240) // 10MB
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Issue Date'),

                        Forms\Components\DatePicker::make('expiry_date')
                            ->label('Expiry Date')
                            ->after('issue_date'),
                    ]),

                Forms\Components\Textarea::make('verification_notes')
                    ->label('Notes')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_type_display')
                    ->label('Type')
                    ->weight(FontWeight::SemiBold)
                    ->sortable('document_type'),

                Tables\Columns\TextColumn::make('document_name')
                    ->label('Name')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('verification_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ClinicDocument $record) => $record->status_badge_color)
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Issued')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Expires')
                    ->date()
                    ->sortable()
                    ->color(fn (ClinicDocument $record) => match(true) {
                        $record->is_expired => 'danger',
                        $record->is_expiring_soon => 'warning',
                        default => null,
                    }),

                Tables\Columns\TextColumn::make('file_size_formatted')
                    ->label('Size')
                    ->sortable('file_size'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('document_type', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('document_type')
                    ->options([
                        'license' => 'Medical License',
                        'certificate' => 'Certificate',
                        'insurance' => 'Insurance Document',
                        'registration' => 'Business Registration',
                        'permit' => 'Operating Permit',
                        'other' => 'Other',
                    ]),

                Tables\Filters\SelectFilter::make('verification_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\TernaryFilter::make('expiry_status')
                    ->label('Expiry Status')
                    ->placeholder('All Documents')
                    ->trueLabel('Expired/Expiring')
                    ->falseLabel('Valid')
                    ->queries(
                        true: fn (Builder $query) => $query->where(function ($q) {
                            $q->where('expiry_date', '<', now())
                              ->orWhere('expiry_date', '<=', now()->addDays(30));
                        }),
                        false: fn (Builder $query) => $query->where('expiry_date', '>', now()->addDays(30)),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (ClinicDocument $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No documents uploaded')
            ->emptyStateDescription('Upload your clinic\'s licenses and important documents.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Upload Document'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // For now, show all documents. 
        // TODO: Filter by user's clinic when user-clinic relationship is established
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinicDocuments::route('/'),
            'create' => Pages\CreateClinicDocument::route('/create'),
            'edit' => Pages\EditClinicDocument::route('/{record}/edit'),
        ];
    }
}
