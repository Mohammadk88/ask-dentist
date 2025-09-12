<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Models\Consultation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsultationPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'ClinicManager', 'Doctor', 'Patient']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Consultation $consultation): bool
    {
        // Patients can view their own consultations
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return true;
        }

        // Doctors can view consultations assigned to them
        if ($user->hasRole('Doctor') && $user->doctor?->id === $consultation->doctor_id) {
            return true;
        }

        // Clinic managers can view all consultations
        return $user->hasRole('ClinicManager');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only patients with valid consent can create consultations
        return $user->hasRole('Patient') && $user->patient?->hasGivenConsent();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Consultation $consultation): bool
    {
        // Patients can update their consultations if still pending
        if ($user->hasRole('Patient') &&
            $user->patient?->id === $consultation->patient_id &&
            $consultation->status === 'pending') {
            return true;
        }

        // Doctors can update consultations assigned to them
        if ($user->hasRole('Doctor') &&
            $user->doctor?->id === $consultation->doctor_id &&
            $user->doctor?->is_verified) {
            return true;
        }

        // Clinic managers can update any consultation
        return $user->hasRole('ClinicManager');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Consultation $consultation): bool
    {
        // Patients can delete their pending consultations
        if ($user->hasRole('Patient') &&
            $user->patient?->id === $consultation->patient_id &&
            $consultation->status === 'pending') {
            return true;
        }

        // Only admins and clinic managers can delete consultations
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can assign the consultation to a doctor.
     */
    public function assign(User $user, Consultation $consultation): bool
    {
        // Only unassigned consultations can be assigned
        if ($consultation->doctor_id) {
            return false;
        }

        // Verified doctors can self-assign
        if ($user->hasRole('Doctor') && $user->doctor?->is_verified) {
            return true;
        }

        // Clinic managers can assign to any doctor
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can start the consultation.
     */
    public function start(User $user, Consultation $consultation): bool
    {
        return $user->hasRole('Doctor') &&
               $user->doctor?->id === $consultation->doctor_id &&
               $consultation->status === 'assigned';
    }

    /**
     * Determine whether the user can complete the consultation.
     */
    public function complete(User $user, Consultation $consultation): bool
    {
        return $user->hasRole('Doctor') &&
               $user->doctor?->id === $consultation->doctor_id &&
               $consultation->status === 'in_progress';
    }

    /**
     * Determine whether the user can cancel the consultation.
     */
    public function cancel(User $user, Consultation $consultation): bool
    {
        // Patients can cancel their own pending/assigned consultations
        if ($user->hasRole('Patient') &&
            $user->patient?->id === $consultation->patient_id &&
            in_array($consultation->status, ['pending', 'assigned'])) {
            return true;
        }

        // Doctors can cancel assigned consultations
        if ($user->hasRole('Doctor') &&
            $user->doctor?->id === $consultation->doctor_id &&
            $consultation->status === 'assigned') {
            return true;
        }

        // Admins and clinic managers can cancel any consultation
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can access medical details.
     */
    public function viewMedicalDetails(User $user, Consultation $consultation): bool
    {
        // Patients can view their own medical details
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return true;
        }

        // Only verified doctors assigned to the consultation can view medical details
        if ($user->hasRole('Doctor') &&
            $user->doctor?->id === $consultation->doctor_id &&
            $user->doctor?->is_verified) {
            return true;
        }

        // Medical professionals with proper access
        return $user->hasRole(['Admin', 'ClinicManager']);
    }
}
