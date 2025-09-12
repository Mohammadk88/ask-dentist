<?php

namespace App\Repositories;

use App\Repositories\Contracts\BeforeAfterRepositoryInterface;
use App\Models\BeforeAfterCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BeforeAfterRepository implements BeforeAfterRepositoryInterface
{
    protected $model;

    public function __construct(BeforeAfterCase $model)
    {
        $this->model = $model;
    }

    /**
     * Get published before/after cases
     */
    public function getPublished(int $limit = 10): Collection
    {
        $cacheKey = "cache:home:before_after";
        
        return Cache::remember($cacheKey, 60, function () use ($limit) {
            return $this->model
                ->where('status', 'published')
                ->where('is_approved', true)
                ->with(['doctor:id,specialty,bio', 'clinic:id,name,address'])
                ->orderByRaw('is_featured DESC, created_at DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get featured cases only
     */
    public function getFeatured(int $limit = 5): Collection
    {
        $cacheKey = "cache:before_after:featured";
        
        return Cache::remember($cacheKey, 120, function () use ($limit) {
            return $this->model
                ->where('status', 'published')
                ->where('is_approved', true)
                ->where('is_featured', true)
                ->with(['doctor:id,specialty,bio', 'clinic:id,name,address'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get cases by doctor
     */
    public function getByDoctor(int $doctorId, int $limit = 20): Collection
    {
        $cacheKey = "cache:before_after:doctor:{$doctorId}";
        
        return Cache::remember($cacheKey, 120, function () use ($doctorId, $limit) {
            return $this->model
                ->where('doctor_id', $doctorId)
                ->where('status', 'published')
                ->where('is_approved', true)
                ->with(['clinic:id,name,address'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get cases by clinic
     */
    public function getByClinic(int $clinicId, int $limit = 20): Collection
    {
        $cacheKey = "cache:before_after:clinic:{$clinicId}";
        
        return Cache::remember($cacheKey, 120, function () use ($clinicId, $limit) {
            return $this->model
                ->where('clinic_id', $clinicId)
                ->where('status', 'published')
                ->where('is_approved', true)
                ->with(['doctor:id,specialty,bio'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get cases by treatment type
     */
    public function getByTreatmentType(string $treatmentType, int $limit = 10): Collection
    {
        $cacheKey = "cache:before_after:treatment:" . md5($treatmentType);
        
        return Cache::remember($cacheKey, 300, function () use ($treatmentType, $limit) {
            return $this->model
                ->where('status', 'published')
                ->where('is_approved', true)
                ->where('treatment_type', 'LIKE', "%{$treatmentType}%")
                ->with(['doctor:id,specialty,bio', 'clinic:id,name,address'])
                ->orderByRaw('is_featured DESC, created_at DESC')
                ->limit($limit)
                ->get();
        });
    }
}