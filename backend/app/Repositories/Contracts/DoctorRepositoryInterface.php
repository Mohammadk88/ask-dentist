<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use App\Models\Doctor;

interface DoctorRepositoryInterface
{
    /**
     * Get top doctors ordered by promotion and rating
     * 
     * @param int $limit
     * @return Collection
     */
    public function top(int $limit = 15): Collection;
    
    /**
     * Get promoted doctors only
     * 
     * @param int $limit
     * @return Collection
     */
    public function getPromoted(int $limit = 5): Collection;
    
    /**
     * Get doctors by specialization
     * 
     * @param array $specializationIds
     * @param int $limit
     * @return Collection
     */
    public function getBySpecialization(array $specializationIds, int $limit = 10): Collection;
    
    /**
     * Get doctors by rating
     * 
     * @param int $limit
     * @param float $minRating
     * @return Collection
     */
    public function getByRating(int $limit = 10, float $minRating = 4.0): Collection;
    
    /**
     * Find doctor by ID with relations
     * 
     * @param int $id
     * @return Doctor|null
     */
    public function findWithRelations(int $id): ?Doctor;
}