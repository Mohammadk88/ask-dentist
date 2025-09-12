<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
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
    public function view(User $user, Message $message): bool
    {
        $consultation = $message->consultation;

        // Users can view messages in consultations they're part of
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return true;
        }

        if ($user->hasRole('Doctor') && $user->doctor?->id === $consultation->doctor_id) {
            return true;
        }

        return $user->hasRole('ClinicManager');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Doctor', 'Patient']);
    }

    /**
     * Determine whether the user can send messages in a consultation.
     */
    public function send(User $user, $consultationId): bool
    {
        $consultation = \App\Models\Consultation::find($consultationId);

        if (!$consultation) {
            return false;
        }

        // Only allow messaging in active consultations
        if (!$consultation->isActive() && $consultation->status !== 'assigned') {
            return false;
        }

        // Patients can send messages to their own consultations
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return true;
        }

        // Doctors can send messages to assigned consultations
        if ($user->hasRole('Doctor') &&
            $user->doctor?->id === $consultation->doctor_id &&
            $user->doctor?->is_verified) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Message $message): bool
    {
        // Users can only edit their own messages within 15 minutes
        if ($user->id === $message->user_id &&
            $message->sent_at->diffInMinutes(now()) <= 15) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Message $message): bool
    {
        // Users can delete their own messages within 30 minutes
        if ($user->id === $message->user_id &&
            $message->sent_at->diffInMinutes(now()) <= 30) {
            return true;
        }

        // Clinic managers can delete any message
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can mark messages as read.
     */
    public function markAsRead(User $user, Message $message): bool
    {
        $consultation = $message->consultation;

        // Users can mark messages as read in their consultations
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return true;
        }

        if ($user->hasRole('Doctor') && $user->doctor?->id === $consultation->doctor_id) {
            return true;
        }

        return false;
    }
}
