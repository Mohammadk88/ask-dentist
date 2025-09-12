<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface BeforeAfterRepositoryInterface
{
    /**
     * Get published before/after cases
     * 
     * @param int $limit
     * @return Collection
     */
    public function getPublished(int $limit = 10): Collection;
    
    /**
     * Get featured cases only
     * 
     * @param int $limit
     * @return Collection
     */
    public function getFeatured(int $limit = 5): Collection;
    
    /**
     * Get cases by doctor
     * 
     * @param int $doctorId
     * @param int $limit
     * @return Collection
     */
    public function getByDoctor(int $doctorId, int $limit = 10): Collection;
    
    /**
     * Get cases by clinic
     * 
     * @param int $clinicId
     * @param int $limit
     * @return Collection
     */
    public function getByClinic(int $clinicId, int $limit = 10): Collection;
    
    /**
     * Get cases by treatment type
     * 
     * @param string $treatmentType
     * @param int $limit
     * @return Collection
     */
    public function getByTreatmentType(string $treatmentType, int $limit = 10): Collection;
}