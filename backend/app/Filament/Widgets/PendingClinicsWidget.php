<?php

namespace App\Filament\Widgets;

use App\Models\Clinic;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingClinicsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Clinic::query()
                    ->whereNull('verified_at')
                    ->latest()
                    ->limit(10)
            )
            ->heading('Pending Clinic Verifications')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Clinic Name')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('city')
                    ->label('Location')
                    ->formatStateUsing(fn ($record) => $record->city . ', ' . $record->country),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Contact'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(function (Clinic $record) {
                        $record->update([
                            'verified_at' => now(),
                            'verification_notes' => 'Quick verified from dashboard on ' . now()->format('M j, Y g:i A'),
                        ]);
                        
                        $this->dispatch('refresh');
                    }),
                
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Clinic $record) => route('filament.admin.resources.clinics.edit', $record)),
            ]);
    }
}