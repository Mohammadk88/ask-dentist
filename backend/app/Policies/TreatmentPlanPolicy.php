<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Infrastructure\Models\TreatmentPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Treatment Plan Policy
 *
 * Defines authorization rules for treatment plan-related actions
 */
class TreatmentPlanPolicy
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
     * Determine whether the user can view any treatment plans.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view treatment plans (scoped by role)
        return true;
    }

    /**
     * Determine whether the user can view the treatment plan.
     */
    public function view(User $user, TreatmentPlan $treatmentPlan): bool
    {
        // Patients can view plans for their own treatment requests
        if ($user->isPatient()) {
            return $treatmentPlan->treatmentRequest->patient->user_id === $user->id;
        }

        // Doctors can view their own treatment plans
        if ($user->isDoctor()) {
            return $treatmentPlan->doctor_id === $user->doctor->id;
        }

        // Clinic managers can view plans for their clinics
        if ($user->isClinicManager()) {
            return $this->clinicManagerCanAccessPlan($user, $treatmentPlan);
        }

        return false;
    }

    /**
     * Determine whether the user can create treatment plans.
     */
    public function create(User $user): bool
    {
        // Only verified doctors can create treatment plans
        return $user->isDoctor() && $user->doctor?->verified_at !== null;
    }

    /**
     * Determine whether the user can update the treatment plan.
     */
    public function update(User $user, TreatmentPlan $treatmentPlan): bool
    {
        // Only the doctor who created the plan can update it
        if ($user->isDoctor()) {
            return $treatmentPlan->doctor_id === $user->doctor->id &&
                   in_array($treatmentPlan->status, ['draft', 'submitted']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the treatment plan.
     */
    public function delete(User $user, TreatmentPlan $treatmentPlan): bool
    {
        // Only the doctor who created the plan can delete it (if it's still a draft)
        if ($user->isDoctor()) {
            return $treatmentPlan->doctor_id === $user->doctor->id &&
                   $treatmentPlan->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can accept the treatment plan.
     */
    public function accept(User $user, TreatmentPlan $treatmentPlan): bool
    {
        // Only the patient can accept their own treatment plan
        if ($user->isPatient()) {
            return $treatmentPlan->treatmentRequest->patient->user_id === $user->id &&
                   $treatmentPlan->status === 'submitted';
        }

        return false;
    }

    /**
     * Determine whether the user can reject the treatment plan.
     */
    public function reject(User $user, TreatmentPlan $treatmentPlan): bool
    {
        // Only the patient can reject their own treatment plan
        if ($user->isPatient()) {
            return $treatmentPlan->treatmentRequest->patient->user_id === $user->id &&
                   $treatmentPlan->status === 'submitted';
        }

        return false;
    }

    /**
     * Check if clinic manager can access treatment plan
     */
    private function clinicManagerCanAccessPlan(User $user, TreatmentPlan $treatmentPlan): bool
    {
        // Check if the treatment plan is for the manager's clinic
        // For now, we'll return true, but in a real implementation,
        // this would check the clinic_manager's clinic association
        return $treatmentPlan->clinic_id !== null;
    }
}
