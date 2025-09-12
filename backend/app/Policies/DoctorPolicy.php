<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Doctor;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorPolicy
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
        return $user->isAdmin() || $user->isClinicManager() || $user->isDoctor() || $user->isPatient();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Doctor $doctor): bool
    {
        // Doctors can view their own profile
        if ($user->hasRole('Doctor') && $user->doctor?->id === $doctor->id) {
            return true;
        }

        // Patients can view verified doctor profiles
        if ($user->hasRole('Patient') && $doctor->is_verified) {
            return true;
        }

        // Admins and clinic managers can view all doctor profiles
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
    public function update(User $user, Doctor $doctor): bool
    {
        // Doctors can update their own profile
        if ($user->hasRole('Doctor') && $user->doctor?->id === $doctor->id) {
            return true;
        }

        // Admins and clinic managers can update any doctor profile
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Doctor $doctor): bool
    {
        // Only admins and clinic managers can delete doctor profiles
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can verify the doctor.
     */
    public function verify(User $user, Doctor $doctor): bool
    {
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can update availability.
     */
    public function updateAvailability(User $user, Doctor $doctor): bool
    {
        // Doctors can update their own availability
        if ($user->hasRole('Doctor') && $user->doctor?->id === $doctor->id) {
            return true;
        }

        // Admins and clinic managers can update any doctor's availability
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can view sensitive information.
     */
    public function viewSensitive(User $user, Doctor $doctor): bool
    {
        // Doctors can view their own sensitive information
        if ($user->hasRole('Doctor') && $user->doctor?->id === $doctor->id) {
            return true;
        }

        // Only admins can view other doctors' sensitive information
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can manage specializations.
     */
    public function manageSpecializations(User $user, Doctor $doctor): bool
    {
        // Doctors can manage their own specializations
        if ($user->hasRole('Doctor') && $user->doctor?->id === $doctor->id) {
            return true;
        }

        // Admins and clinic managers can manage any doctor's specializations
        return $user->hasRole(['Admin', 'ClinicManager']);
    }
}
