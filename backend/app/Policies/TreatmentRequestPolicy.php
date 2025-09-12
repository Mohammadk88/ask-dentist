<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Models\TreatmentRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class TreatmentRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any treatment requests.
     */
    public function viewAny(User $user): bool
    {
        // Admin and ClinicManager can view all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Doctors can view requests assigned to them
        // Patients can view their own requests
        return $user->hasRole(['Doctor', 'Patient']);
    }

    /**
     * Determine whether the user can view the treatment request.
     */
    public function view(User $user, TreatmentRequest $treatmentRequest): bool
    {
        // Admin and ClinicManager can view all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Patient can only view their own requests
        if ($user->hasRole('Patient')) {
            return $treatmentRequest->patient_id === $user->id;
        }

        // Doctor can view requests assigned to them or in their clinic
        if ($user->hasRole('Doctor') && $user->doctor) {
            // Check if doctor is assigned to this request
            $isAssigned = $treatmentRequest->requestDoctors()
                ->where('doctor_id', $user->doctor->id)
                ->exists();

            if ($isAssigned) {
                return true;
            }

            // Check if request is from their clinic
            return $treatmentRequest->clinic_id === $user->doctor->clinic_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create treatment requests.
     */
    public function create(User $user): bool
    {
        // Only patients can create treatment requests
        return $user->hasRole('Patient');
    }

    /**
     * Determine whether the user can update the treatment request.
     */
    public function update(User $user, TreatmentRequest $treatmentRequest): bool
    {
        // Admin and ClinicManager can update all
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Patient can update their own requests (before submission)
        if ($user->hasRole('Patient')) {
            return $treatmentRequest->patient_id === $user->id &&
                   $treatmentRequest->status === 'draft';
        }

        // Doctor can update status of assigned requests
        if ($user->hasRole('Doctor') && $user->doctor) {
            return $treatmentRequest->requestDoctors()
                ->where('doctor_id', $user->doctor->id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the treatment request.
     */
    public function delete(User $user, TreatmentRequest $treatmentRequest): bool
    {
        // Only Admin and ClinicManager can delete
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Patient can delete their own draft requests
        if ($user->hasRole('Patient')) {
            return $treatmentRequest->patient_id === $user->id &&
                   $treatmentRequest->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can assign doctors to the treatment request.
     */
    public function assignDoctor(User $user, TreatmentRequest $treatmentRequest): bool
    {
        // Admin and ClinicManager can assign doctors
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can create treatment plans.
     */
    public function createTreatmentPlan(User $user, TreatmentRequest $treatmentRequest): bool
    {
        // Admin and ClinicManager can always create plans
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // Doctor can create plans for assigned requests
        if ($user->hasRole('Doctor') && $user->doctor) {
            return $treatmentRequest->requestDoctors()
                ->where('doctor_id', $user->doctor->id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can dispatch the treatment request to doctors.
     */
    public function dispatch(User $user, TreatmentRequest $treatmentRequest): bool
    {
        // Admin can dispatch any treatment request
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Patient can dispatch their own treatment request
        if ($user->hasRole('Patient')) {
            return $treatmentRequest->patient_id === $user->id;
        }

        return false;
    }
}
