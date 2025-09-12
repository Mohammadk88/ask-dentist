<?php

namespace App\Repositories;

use App\Repositories\Contracts\FavoritesRepositoryInterface;
use App\Models\Favorite;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FavoritesRepository implements FavoritesRepositoryInterface
{
    protected $favoriteModel;
    protected $doctorModel;
    protected $clinicModel;

    public function __construct(
        Favorite $favoriteModel,
        Doctor $doctorModel,
        Clinic $clinicModel
    ) {
        $this->favoriteModel = $favoriteModel;
        $this->doctorModel = $doctorModel;
        $this->clinicModel = $clinicModel;
    }

    /**
     * Get patient's favorite doctors
     */
    public function getFavoriteDoctors(string $patientId): Collection
    {
        $cacheKey = "cache:home:favorites:doctors:{$patientId}";
        
        return Cache::remember($cacheKey, 60, function () use ($patientId) {
            return $this->doctorModel
                ->select(['id', 'specialty', 'bio', 'rating_avg', 'rating_count'])
                ->with(['clinics:id,name,address'])
                ->whereIn('id', function ($query) use ($patientId) {
                    $query->select('favoritable_id')
                          ->from('favorites')
                          ->where('user_id', $patientId)
                          ->where('favoritable_type', Doctor::class);
                })
                ->orderBy('rating_avg', 'desc')
                ->get();
        });
    }

    /**
     * Get patient's favorite clinics
     */
    public function getFavoriteClinics(string $patientId): Collection
    {
        $cacheKey = "cache:home:favorites:clinics:{$patientId}";
        
        return Cache::remember($cacheKey, 60, function () use ($patientId) {
            return $this->clinicModel
                ->select(['id', 'name', 'address', 'rating_avg', 'rating_count'])
                ->whereIn('id', function ($query) use ($patientId) {
                    $query->select('favoritable_id')
                          ->from('favorites')
                          ->where('user_id', $patientId)
                          ->where('favoritable_type', Clinic::class);
                })
                ->orderBy('rating_avg', 'desc')
                ->get();
        });
    }

    /**
     * Toggle favorite doctor status
     */
    public function toggleDoctorFavorite(string $patientId, int $doctorId): bool
    {
        $this->clearFavoriteCaches($patientId);
        
        $existing = $this->favoriteModel
            ->where('user_id', $patientId)
            ->where('favoritable_id', $doctorId)
            ->where('favoritable_type', Doctor::class)
            ->first();

        if ($existing) {
            $existing->delete();
            return false; // Removed from favorites
        } else {
            $this->favoriteModel->create([
                'user_id' => $patientId,
                'favoritable_id' => $doctorId,
                'favoritable_type' => Doctor::class,
            ]);
            return true; // Added to favorites
        }
    }

    /**
     * Toggle favorite clinic status
     */
    public function toggleClinicFavorite(string $patientId, int $clinicId): bool
    {
        $this->clearFavoriteCaches($patientId);
        
        $existing = $this->favoriteModel
            ->where('user_id', $patientId)
            ->where('favoritable_id', $clinicId)
            ->where('favoritable_type', Clinic::class)
            ->first();

        if ($existing) {
            $existing->delete();
            return false; // Removed from favorites
        } else {
            $this->favoriteModel->create([
                'user_id' => $patientId,
                'favoritable_id' => $clinicId,
                'favoritable_type' => Clinic::class,
            ]);
            return true; // Added to favorites
        }
    }

    /**
     * Check if doctor is favorited by patient
     */
    public function isDoctorFavorited(string $patientId, int $doctorId): bool
    {
        return $this->favoriteModel
            ->where('user_id', $patientId)
            ->where('favoritable_id', $doctorId)
            ->where('favoritable_type', Doctor::class)
            ->exists();
    }

    /**
     * Check if clinic is favorited by patient
     */
    public function isClinicFavorited(string $patientId, int $clinicId): bool
    {
        return $this->favoriteModel
            ->where('user_id', $patientId)
            ->where('favoritable_id', $clinicId)
            ->where('favoritable_type', Clinic::class)
            ->exists();
    }

    /**
     * Get all favorites for patient (combined doctors and clinics)
     */
    public function getAllFavorites(string $patientId): array
    {
        $cacheKey = "cache:favorites:all:{$patientId}";
        
        return Cache::remember($cacheKey, 60, function () use ($patientId) {
            return [
                'doctors' => $this->getFavoriteDoctors($patientId),
                'clinics' => $this->getFavoriteClinics($patientId),
            ];
        });
    }

    /**
     * Get favorite counts for patient
     */
    public function getFavoriteCounts(string $patientId): array
    {
        $cacheKey = "cache:favorites:counts:{$patientId}";
        
        return Cache::remember($cacheKey, 300, function () use ($patientId) {
            return [
                'doctors_count' => $this->favoriteModel
                    ->where('user_id', $patientId)
                    ->where('favoritable_type', Doctor::class)
                    ->count(),
                'clinics_count' => $this->favoriteModel
                    ->where('user_id', $patientId)
                    ->where('favoritable_type', Clinic::class)
                    ->count(),
            ];
        });
    }

    /**
     * Clear favorite caches for patient
     */
    protected function clearFavoriteCaches(string $patientId): void
    {
        Cache::forget("cache:home:favorites:doctors:{$patientId}");
        Cache::forget("cache:home:favorites:clinics:{$patientId}");
        Cache::forget("cache:favorites:all:{$patientId}");
        Cache::forget("cache:favorites:counts:{$patientId}");
    }

    /**
     * Remove all favorites for a patient
     */
    public function clearAllFavorites(string $patientId): void
    {
        $this->favoriteModel->where('user_id', $patientId)->delete();
        $this->clearFavoriteCaches($patientId);
    }
}