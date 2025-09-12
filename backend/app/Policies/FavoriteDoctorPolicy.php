<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Models\Doctor;
use Illuminate\Auth\Access\Response;

class FavoriteDoctorPolicy
{
    /**
     * Determine whether the user can view any favorite doctors.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Patient');
    }

    /**
     * Determine whether the user can view the favorite doctors list.
     */
    public function view(User $user): bool
    {
        return $user->hasRole('Patient');
    }

    /**
     * Determine whether the user can toggle a doctor as favorite.
     */
    public function toggle(User $user, Doctor $doctor): Response
    {
        // Only patients can toggle favorites
        if (!$user->hasRole('Patient')) {
            return Response::deny('Only patients can manage favorite doctors.');
        }

        // Can't favorite yourself if you're also a doctor
        if ($user->doctor && $user->doctor->id === $doctor->id) {
            return Response::deny('You cannot add yourself to favorites.');
        }

        // Doctor must be verified and active
        if (!$doctor->is_verified) {
            return Response::deny('You can only favorite verified doctors.');
        }

        // Doctor must not be suspended
        if ($doctor->status === 'suspended') {
            return Response::deny('This doctor is currently unavailable.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can manage their favorite doctors.
     */
    public function manage(User $user): bool
    {
        return $user->hasRole('Patient');
    }

    /**
     * Determine whether the user can manage their favorite doctors.
     * Alias for manage() method to match controller usage.
     */
    public function manageFavorites(User $user): bool
    {
        return $this->manage($user);
    }
}