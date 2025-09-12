<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isClinicManager() || $user->isDoctor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        // Patients can view their own profile
        if ($user->hasRole('Patient') && $user->patient?->id === $patient->id) {
            return true;
        }

        // Doctors can view patients in their consultations
        if ($user->hasRole('Doctor') && $user->doctor?->is_verified) {
            $hasConsultation = $patient->consultations()
                ->where('doctor_id', $user->doctor->id)
                ->exists();

            if ($hasConsultation) {
                return true;
            }
        }

        // Admins and clinic managers can view all patient profiles
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
    {
        // Patients can update their own profile
        if ($user->hasRole('Patient') && $user->patient?->id === $patient->id) {
            return true;
        }

        // Admins and clinic managers can update any patient profile
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
    {
        // Only admins and clinic managers can delete patient profiles
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can view medical records.
     */
    public function viewMedicalRecords(User $user, Patient $patient): bool
    {
        // Patients can view their own medical records
        if ($user->hasRole('Patient') && $user->patient?->id === $patient->id) {
            return true;
        }

        // Doctors can view medical records of patients in their consultations
        if ($user->hasRole('Doctor') && $user->doctor?->is_verified) {
            $hasActiveConsultation = $patient->consultations()
                ->where('doctor_id', $user->doctor->id)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->exists();

            if ($hasActiveConsultation) {
                return true;
            }
        }

        // Medical professionals with proper access
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can update medical records.
     */
    public function updateMedicalRecords(User $user, Patient $patient): bool
    {
        // Patients can update their own basic medical information
        if ($user->hasRole('Patient') && $user->patient?->id === $patient->id) {
            return true;
        }

        // Doctors can update medical records for patients in their consultations
        if ($user->hasRole('Doctor') && $user->doctor?->is_verified) {
            $hasActiveConsultation = $patient->consultations()
                ->where('doctor_id', $user->doctor->id)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->exists();

            if ($hasActiveConsultation) {
                return true;
            }
        }

        // Admins and clinic managers can update any medical records
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can view sensitive information.
     */
    public function viewSensitive(User $user, Patient $patient): bool
    {
        // Patients can view their own sensitive information
        if ($user->hasRole('Patient') && $user->patient?->id === $patient->id) {
            return true;
        }

        // Only medical professionals can view patient sensitive information
        return $user->hasRole(['Admin', 'ClinicManager', 'Doctor']) &&
               ($user->hasRole(['Admin', 'ClinicManager']) || $user->doctor?->is_verified);
    }

    /**
     * Determine whether the user can manage consent.
     */
    public function manageConsent(User $user, Patient $patient): bool
    {
        // Patients can manage their own consent
        if ($user->hasRole('Patient') && $user->patient?->id === $patient->id) {
            return true;
        }

        // Admins and clinic managers can manage consent
        return $user->hasRole(['Admin', 'ClinicManager']);
    }
}
