<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\ModuleToggle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users on the platform')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Doctors', Doctor::count())
                ->description(Doctor::whereNotNull('verified_at')->count() . ' verified')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Total Clinics', Clinic::count())
                ->description(Clinic::whereNotNull('verified_at')->count() . ' verified')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info'),

            Stat::make('Pending Verifications', 
                Doctor::whereNull('verified_at')->count() + 
                Clinic::whereNull('verified_at')->count()
            )
                ->description('Doctors & clinics awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Active Modules', ModuleToggle::where('is_enabled', true)->count())
                ->description('Out of ' . ModuleToggle::count() . ' total modules')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('success'),

            Stat::make('New Registrations', User::where('created_at', '>=', now()->subDays(7))->count())
                ->description('In the last 7 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}