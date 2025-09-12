<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingItemsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Doctor::query()
                    ->whereNull('verified_at')
                    ->with('user')
                    ->latest()
                    ->limit(10)
            )
            ->heading('Pending Doctor Verifications')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Doctor Name')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('specialty')
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
                    ->label('License'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(function (Doctor $record) {
                        $record->update([
                            'verified_at' => now(),
                            'verification_notes' => 'Quick verified from dashboard on ' . now()->format('M j, Y g:i A'),
                        ]);
                        
                        $this->dispatch('refresh');
                    }),
                
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Doctor $record) => route('filament.admin.resources.doctors.edit', $record)),
            ]);
    }
}