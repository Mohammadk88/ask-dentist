<?php

namespace App\Repositories;

use App\Repositories\Contracts\StoryRepositoryInterface;
use App\Models\Story;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class StoryRepository implements StoryRepositoryInterface
{
    protected $model;

    public function __construct(Story $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch active stories for a given locale
     * Order by: is_ad DESC (ads first), created_at DESC (newest first)
     */
    public function fetchActive(string $locale, int $limit = 20): Collection
    {
        $cacheKey = "cache:home:stories:{$locale}";
        
        return Cache::remember($cacheKey, 30, function () use ($locale, $limit) {
            $now = Carbon::now();
            
            return $this->model
                ->where('starts_at', '<=', $now)
                ->where('expires_at', '>', $now)
                ->where('lang', $locale)
                ->with(['doctor:id,full_name,avatar_url', 'clinic:id,name,logo_url'])
                ->orderByRaw('is_ad DESC, created_at DESC')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get stories by owner (doctor or clinic)
     */
    public function getByOwner(string $ownerType, int $ownerId, int $limit = 10): Collection
    {
        $cacheKey = "cache:stories:owner:{$ownerType}:{$ownerId}";
        
        return Cache::remember($cacheKey, 60, function () use ($ownerType, $ownerId, $limit) {
            $now = Carbon::now();
            
            return $this->model
                ->where('owner_type', $ownerType)
                ->where('owner_id', $ownerId)
                ->where('starts_at', '<=', $now)
                ->where('expires_at', '>', $now)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get ad stories only
     */
    public function getAds(string $locale, int $limit = 5): Collection
    {
        $cacheKey = "cache:stories:ads:{$locale}";
        
        return Cache::remember($cacheKey, 60, function () use ($locale, $limit) {
            $now = Carbon::now();
            
            return $this->model
                ->where('starts_at', '<=', $now)
                ->where('expires_at', '>', $now)
                ->where('lang', $locale)
                ->where('is_ad', true)
                ->with(['doctor:id,full_name,avatar_url', 'clinic:id,name,logo_url'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get organic (non-ad) stories only
     */
    public function getOrganic(string $locale, int $limit = 15): Collection
    {
        $cacheKey = "cache:stories:organic:{$locale}";
        
        return Cache::remember($cacheKey, 60, function () use ($locale, $limit) {
            $now = Carbon::now();
            
            return $this->model
                ->where('starts_at', '<=', $now)
                ->where('expires_at', '>', $now)
                ->where('lang', $locale)
                ->where('is_ad', false)
                ->with(['doctor:id,full_name,avatar_url', 'clinic:id,name,logo_url'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}