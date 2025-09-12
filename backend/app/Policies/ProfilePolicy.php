<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Models\ProfilePatient;
use App\Models\ProfileDoctor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view their own profile.
     */
    public function viewOwn(User $user): bool
    {
        return true; // Users can always view their own profile
    }

    /**
     * Determine whether the user can view any profile.
     */
    public function viewAny(User $user): bool
    {
        // Admin and ClinicManager can view all profiles
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can view the patient profile.
     */
    public function viewPatientProfile(User $user, ProfilePatient $profile): bool
    {
        // Admin and ClinicManager can view all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Patient can view their own profile
        if ($user->hasRole('Patient') && $user->profilePatient) {
            return $profile->id === $user->profilePatient->id;
        }

        // Doctor can view patient profiles for their assigned cases
        if ($user->hasRole('Doctor') && $user->doctor) {
            // Check if doctor has any treatment requests with this patient
            return $user->doctor->treatmentRequests()
                ->where('patient_id', $profile->user_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the doctor profile.
     */
    public function viewDoctorProfile(User $user, ProfileDoctor $profile): bool
    {
        // Admin and ClinicManager can view all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Doctor can view their own profile
        if ($user->hasRole('Doctor') && $user->doctor) {
            return $profile->id === $user->doctor->id;
        }

        // Patients can view doctor profiles (for selection purposes)
        if ($user->hasRole('Patient')) {
            return $profile->is_verified && $profile->is_available;
        }

        return false;
    }

    /**
     * Determine whether the user can update their own profile.
     */
    public function updateOwn(User $user): bool
    {
        return true; // Users can always update their own profile
    }

    /**
     * Determine whether the user can update the patient profile.
     */
    public function updatePatientProfile(User $user, ProfilePatient $profile): bool
    {
        // Admin and ClinicManager can update all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Patient can update their own profile
        if ($user->hasRole('Patient') && $user->profilePatient) {
            return $profile->id === $user->profilePatient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the doctor profile.
     */
    public function updateDoctorProfile(User $user, ProfileDoctor $profile): bool
    {
        // Admin and ClinicManager can update all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Doctor can update their own profile
        if ($user->hasRole('Doctor') && $user->doctor) {
            return $profile->id === $user->doctor->id;
        }

        return false;
    }

    /**
     * Determine whether the user can view medical records.
     */
    public function viewMedicalRecords(User $user, ProfilePatient $profile): bool
    {
        // Admin and ClinicManager can view all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Patient can view their own medical records
        if ($user->hasRole('Patient') && $user->profilePatient) {
            return $profile->id === $user->profilePatient->id;
        }

        // Doctor can view medical records for their assigned patients
        if ($user->hasRole('Doctor') && $user->doctor) {
            return $user->doctor->treatmentRequests()
                ->where('patient_id', $profile->user_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update medical records.
     */
    public function updateMedicalRecords(User $user, ProfilePatient $profile): bool
    {
        // Admin and ClinicManager can update all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Only doctors can update medical records (not patients)
        if ($user->hasRole('Doctor') && $user->doctor) {
            return $user->doctor->treatmentRequests()
                ->where('patient_id', $profile->user_id)
                ->exists();
        }

        return false;
    }
}
