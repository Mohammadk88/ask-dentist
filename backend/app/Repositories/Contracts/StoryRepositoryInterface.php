<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface StoryRepositoryInterface
{
    /**
     * Fetch active stories for a given locale
     * 
     * @param string $locale
     * @param int $limit
     * @return Collection
     */
    public function fetchActive(string $locale, int $limit = 20): Collection;
    
    /**
     * Get stories by owner
     * 
     * @param string $ownerType
     * @param int $ownerId
     * @param int $limit
     * @return Collection
     */
    public function getByOwner(string $ownerType, int $ownerId, int $limit = 10): Collection;
    
    /**
     * Get ad stories only
     * 
     * @param string $locale
     * @param int $limit
     * @return Collection
     */
    public function getAds(string $locale, int $limit = 5): Collection;
    
    /**
     * Get organic (non-ad) stories only
     * 
     * @param string $locale
     * @param int $limit
     * @return Collection
     */
    public function getOrganic(string $locale, int $limit = 15): Collection;
}