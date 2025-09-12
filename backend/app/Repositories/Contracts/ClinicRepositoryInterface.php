<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use App\Models\Clinic;

interface ClinicRepositoryInterface
{
    /**
     * Get top clinics ordered by promotion and rating
     * 
     * @param int $limit
     * @return Collection
     */
    public function top(int $limit = 10): Collection;
    
    /**
     * Get promoted clinics only
     * 
     * @param int $limit
     * @return Collection
     */
    public function getPromoted(int $limit = 5): Collection;
    
    /**
     * Get clinics by rating
     * 
     * @param int $limit
     * @param float $minRating
     * @return Collection
     */
    public function getByRating(int $limit = 10, float $minRating = 4.0): Collection;
    
    /**
     * Find clinic by ID with relations
     * 
     * @param int $id
     * @return Clinic|null
     */
    public function findWithRelations(int $id): ?Clinic;
}