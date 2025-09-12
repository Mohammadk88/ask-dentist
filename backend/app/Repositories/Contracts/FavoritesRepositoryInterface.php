<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface FavoritesRepositoryInterface
{
    /**
     * Get favorite doctors for a patient
     * 
     * @param string $patientId
     * @return Collection
     */
    public function getFavoriteDoctors(string $patientId): Collection;
    
    /**
     * Get favorite clinics for a patient
     * 
     * @param string $patientId
     * @return Collection
     */
    public function getFavoriteClinics(string $patientId): Collection;
    
    /**
     * Toggle doctor favorite status
     * 
     * @param string $patientId
     * @param int $doctorId
     * @return bool Returns true if added, false if removed
     */
    public function toggleDoctorFavorite(string $patientId, int $doctorId): bool;
    
    /**
     * Toggle clinic favorite status
     * 
     * @param string $patientId
     * @param int $clinicId
     * @return bool Returns true if added, false if removed
     */
    public function toggleClinicFavorite(string $patientId, int $clinicId): bool;
    
    /**
     * Check if doctor is favorited by patient
     * 
     * @param string $patientId
     * @param int $doctorId
     * @return bool
     */
    public function isDoctorFavorited(string $patientId, int $doctorId): bool;
    
    /**
     * Check if clinic is favorited by patient
     * 
     * @param string $patientId
     * @param int $clinicId
     * @return bool
     */
    public function isClinicFavorited(string $patientId, int $clinicId): bool;
    
    /**
     * Remove all favorites for a patient
     * 
     * @param string $patientId
     * @return void
     */
    public function clearAllFavorites(string $patientId): void;
}