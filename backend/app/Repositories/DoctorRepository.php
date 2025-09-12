<?php

namespace App\Repositories;

use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Models\Doctor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DoctorRepository implements DoctorRepositoryInterface
{
    protected $model;

    public function __construct(Doctor $model)
    {
        $this->model = $model;
    }

    /**
     * Get top doctors ordered by promotion and rating with clinic relation
     * Order by: is_promoted DESC, rating_avg DESC, rating_count DESC
     */
    public function top(int $limit = 10): Collection
    {
        $cacheKey = "cache:home:top_doctors";
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            return $this->model
                ->select(['id', 'specialty', 'bio', 'is_promoted', 'promoted_until', 'rating_avg', 'rating_count'])
                ->with(['clinics:id,name,address'])
                ->where(function ($query) {
                    $query->where('is_promoted', true)
                          ->whereDate('promoted_until', '>=', now())
                          ->orWhere('is_promoted', false);
                })
                ->orderByRaw('is_promoted DESC, rating_avg DESC, rating_count DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get promoted doctors only
     */
    public function getPromoted(int $limit = 5): Collection
    {
        $cacheKey = "cache:doctors:promoted";
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            return $this->model
                ->with(['clinics:id,name,location'])
                ->where('is_promoted', true)
                ->whereDate('promoted_until', '>=', now())
                ->orderBy('rating_avg', 'desc')
                ->orderBy('rating_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get organic (non-promoted) doctors
     */
    public function getOrganic(int $limit = 10): Collection
    {
        $cacheKey = "cache:doctors:organic";
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            return $this->model
                ->with(['clinics:id,name,location'])
                ->where('is_promoted', false)
                ->orderBy('rating_avg', 'desc')
                ->orderBy('rating_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get doctors by specialization
     */
    public function getBySpecialization(array $specializationIds, int $limit = 10): Collection
    {
        $cacheKey = "cache:doctors:specialization:" . md5(implode(',', $specializationIds));
        
        return Cache::remember($cacheKey, 120, function () use ($specializationIds, $limit) {
            return $this->model
                ->with(['clinics:id,name,location,logo_url'])
                ->whereIn('specialization_id', $specializationIds)
                ->orderByRaw('is_promoted DESC, rating_avg DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get doctors by clinic
     */
    public function getByClinic(int $clinicId, int $limit = 20): Collection
    {
        $cacheKey = "cache:doctors:clinic:{$clinicId}";
        
        return Cache::remember($cacheKey, 120, function () use ($clinicId, $limit) {
            return $this->model
                ->with(['clinics:id,name,location,logo_url'])
                ->whereHas('clinics', function ($query) use ($clinicId) {
                    $query->where('clinics.id', $clinicId);
                })
                ->orderBy('rating_avg', 'desc')
                ->orderBy('rating_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Search doctors by name or specialization
     */
    public function search(string $query, int $limit = 20): Collection
    {
        $cacheKey = "cache:doctors:search:" . md5($query);
        
        return Cache::remember($cacheKey, 300, function () use ($query, $limit) {
            return $this->model
                ->with(['clinics:id,name,location,logo_url'])
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'LIKE', "%{$query}%")
                      ->orWhere('specialization', 'LIKE', "%{$query}%")
                      ->orWhere('bio', 'LIKE', "%{$query}%");
                })
                ->orderByRaw('is_promoted DESC, rating_avg DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get doctors by rating range
     */
    public function getByRating(int $limit = 10, float $minRating = 4.0): Collection
    {
        $cacheKey = "cache:doctors:rating:{$minRating}";
        
        return Cache::remember($cacheKey, 120, function () use ($minRating, $limit) {
            return $this->model
                ->with(['clinics:id,name,location,logo_url'])
                ->where('rating_avg', '>=', $minRating)
                ->orderBy('rating_avg', 'desc')
                ->orderBy('rating_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Find doctor by ID with relations
     */
    public function findWithRelations(int $id): ?Doctor
    {
        $cacheKey = "cache:doctor:details:{$id}";
        
        return Cache::remember($cacheKey, 300, function () use ($id) {
            return $this->model
                ->with(['clinics:id,name,location,logo_url'])
                ->find($id);
        });
    }
}