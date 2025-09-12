<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Models\Clinic;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClinicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any clinics.
     */
    public function viewAny(User $user): bool
    {
        // Everyone can view clinics (for patient selection)
        return true;
    }

    /**
     * Determine whether the user can view the clinic.
     */
    public function view(User $user, Clinic $clinic): bool
    {
        // Everyone can view basic clinic information
        return true;
    }

    /**
     * Determine whether the user can create clinics.
     */
    public function create(User $user): bool
    {
        // Only Admin can create clinics
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the clinic.
     */
    public function update(User $user, Clinic $clinic): bool
    {
        // Admin can update all clinics
        if ($user->hasRole('Admin')) {
            return true;
        }

        // ClinicManager can update their own clinic
        if ($user->hasRole('ClinicManager') && $user->doctor) {
            return $clinic->id === $user->doctor->clinic_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the clinic.
     */
    public function delete(User $user, Clinic $clinic): bool
    {
        // Only Admin can delete clinics
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can manage clinic doctors.
     */
    public function manageDoctors(User $user, Clinic $clinic): bool
    {
        // Admin can manage all clinic doctors
        if ($user->hasRole('Admin')) {
            return true;
        }

        // ClinicManager can manage their own clinic doctors
        if ($user->hasRole('ClinicManager') && $user->doctor) {
            return $clinic->id === $user->doctor->clinic_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view clinic reports.
     */
    public function viewReports(User $user, Clinic $clinic): bool
    {
        // Admin can view all clinic reports
        if ($user->hasRole('Admin')) {
            return true;
        }

        // ClinicManager can view their own clinic reports
        if ($user->hasRole('ClinicManager') && $user->doctor) {
            return $clinic->id === $user->doctor->clinic_id;
        }

        return false;
    }

    /**
     * Determine whether the user can manage clinic settings.
     */
    public function manageSettings(User $user, Clinic $clinic): bool
    {
        // Admin can manage all clinic settings
        if ($user->hasRole('Admin')) {
            return true;
        }

        // ClinicManager can manage their own clinic settings
        if ($user->hasRole('ClinicManager') && $user->doctor) {
            return $clinic->id === $user->doctor->clinic_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view clinic finances.
     */
    public function viewFinances(User $user, Clinic $clinic): bool
    {
        // Only Admin can view clinic finances
        if ($user->hasRole('Admin')) {
            return true;
        }

        // ClinicManager can view their own clinic finances
        if ($user->hasRole('ClinicManager') && $user->doctor) {
            return $clinic->id === $user->doctor->clinic_id;
        }

        return false;
    }
}
