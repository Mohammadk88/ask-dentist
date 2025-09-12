<?php

namespace App\Repositories;

use App\Repositories\Contracts\ClinicRepositoryInterface;
use App\Models\Clinic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClinicRepository implements ClinicRepositoryInterface
{
    protected $model;

    public function __construct(Clinic $model)
    {
        $this->model = $model;
    }

    /**
     * Get top clinics ordered by promotion and rating
     * Order by: is_promoted DESC, rating_avg DESC, rating_count DESC
     */
    public function top(int $limit = 10): Collection
    {
        $cacheKey = "cache:home:top_clinics";
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            return $this->model
                ->select(['id', 'name', 'description', 'location', 'logo_url', 'cover_path', 
                         'is_promoted', 'promoted_until', 'rating_avg', 'rating_count'])
                ->whereDate('promoted_until', '>=', now())
                ->orWhere('is_promoted', false)
                ->orderByRaw('is_promoted DESC, rating_avg DESC, rating_count DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get promoted clinics only
     */
    public function getPromoted(int $limit = 5): Collection
    {
        $cacheKey = "cache:clinics:promoted";
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            return $this->model
                ->where('is_promoted', true)
                ->whereDate('promoted_until', '>=', now())
                ->orderBy('rating_avg', 'desc')
                ->orderBy('rating_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get organic (non-promoted) clinics
     */
    public function getOrganic(int $limit = 10): Collection
    {
        $cacheKey = "cache:clinics:organic";
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            return $this->model
                ->where('is_promoted', false)
                ->orderBy('rating_avg', 'desc')
                ->orderBy('rating_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Search clinics by name or location
     */
    public function search(string $query, int $limit = 20): Collection
    {
        $cacheKey = "cache:clinics:search:" . md5($query);
        
        return Cache::remember($cacheKey, 300, function () use ($query, $limit) {
            return $this->model
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->orderByRaw('is_promoted DESC, rating_avg DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get clinics by rating range
     */
    public function getByRating(int $limit = 10, float $minRating = 4.0): Collection
    {
        $cacheKey = "cache:clinics:rating:{$minRating}";
        
        return Cache::remember($cacheKey, 120, function () use ($minRating, $limit) {
            return $this->model
                ->where('rating_avg', '>=', $minRating)
                ->orderBy('rating_avg', 'desc')
                ->orderBy('rating_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Find clinic by ID with relations
     */
    public function findWithRelations(int $id): ?Clinic
    {
        $cacheKey = "cache:clinic:details:{$id}";
        
        return Cache::remember($cacheKey, 300, function () use ($id) {
            return $this->model
                ->with(['doctors:id,full_name,specialization,avatar_url,clinic_id'])
                ->find($id);
        });
    }
}