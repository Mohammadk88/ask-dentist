<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Consultation;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
                
            Stat::make('Active Clinics', Clinic::whereNotNull('verified_at')->count())
                ->description('Verified clinics')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),
                
            Stat::make('Verified Doctors', Doctor::whereNotNull('verified_at')->count())
                ->description('Active medical professionals')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('info'),
                
            Stat::make('Total Consultations', Consultation::count())
                ->description('All consultation requests')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('warning'),
        ];
    }
}