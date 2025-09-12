<?php

namespace App\Filament\Widgets;

use App\Models\Story;
use App\Models\Doctor;
use App\Models\BeforeAfterCase;
use App\Models\Clinic;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Active Stories', $this->getActiveStoriesCount())
                ->description('Currently published stories')
                ->descriptionIcon('heroicon-m-play')
                ->color('success')
                ->chart($this->getStoriesChart()),
                
            Stat::make('Promoted Doctors', $this->getPromotedDoctorsCount())
                ->description('Doctors with active promotion')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('warning'),
                
            Stat::make('Featured Before/After', $this->getFeaturedBeforeAfterCount())
                ->description('Approved showcase cases')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info'),
                
            Stat::make('Promoted Clinics', $this->getPromotedClinicsCount())
                ->description('Clinics with active promotion')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
                
            Stat::make('Total Reviews', $this->getTotalReviewsCount())
                ->description('Average rating: ' . $this->getAverageRating())
                ->descriptionIcon('heroicon-m-star')
                ->color('secondary'),
                
            Stat::make('Verified Entities', $this->getVerifiedEntitiesCount())
                ->description('Verified doctors & clinics')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
    
    private function getActiveStoriesCount(): int
    {
        return Story::where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->where(function ($query) {
                $query->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', now());
            })
            ->count();
    }
    
    private function getPromotedDoctorsCount(): int
    {
        return Doctor::where('is_promoted', true)
            ->where(function ($query) {
                $query->whereNull('promoted_until')
                      ->orWhere('promoted_until', '>', now());
            })
            ->count();
    }
    
    private function getFeaturedBeforeAfterCount(): int
    {
        return BeforeAfterCase::where('status', 'approved')
            ->count();
    }
    
    private function getPromotedClinicsCount(): int
    {
        return Clinic::where('is_promoted', true)
            ->where(function ($query) {
                $query->whereNull('promoted_until')
                      ->orWhere('promoted_until', '>', now());
            })
            ->count();
    }
    
    private function getTotalReviewsCount(): int
    {
        return Review::count();
    }
    
    private function getAverageRating(): string
    {
        $avg = Review::avg('rating');
        return $avg ? number_format($avg, 1) . '/5' : 'N/A';
    }
    
    private function getVerifiedEntitiesCount(): int
    {
        $verifiedDoctors = Doctor::whereNotNull('verified_at')->count();
        $verifiedClinics = Clinic::whereNotNull('verified_at')->count();
        return $verifiedDoctors + $verifiedClinics;
    }
    
    private function getStoriesChart(): array
    {
        // Get stories created in the last 7 days for trending chart
        $stories = Story::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
            
        // Pad with zeros if less than 7 days
        return array_pad($stories, 7, 0);
    }
}