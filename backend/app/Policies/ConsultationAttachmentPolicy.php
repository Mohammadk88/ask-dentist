<?php

namespace App\Policies;

use App\Infrastructure\Models\User;
use App\Models\ConsultationAttachment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsultationAttachmentPolicy
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
    public function view(User $user, ConsultationAttachment $attachment): bool
    {
        $consultation = $attachment->consultation;

        // Log file access for audit
        activity('file_access')
            ->causedBy($user)
            ->performedOn($attachment)
            ->withProperties([
                'file_name' => $attachment->file_name,
                'consultation_id' => $consultation->id,
                'action' => 'viewed'
            ])
            ->log('File attachment viewed');

        // Users can view attachments in consultations they're part of
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
     * Determine whether the user can upload attachments to a consultation.
     */
    public function upload(User $user, $consultationId): bool
    {
        $consultation = \App\Models\Consultation::find($consultationId);

        if (!$consultation) {
            return false;
        }

        // Patients can upload to their own consultations
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return true;
        }

        // Doctors can upload to assigned consultations
        if ($user->hasRole('Doctor') &&
            $user->doctor?->id === $consultation->doctor_id &&
            $user->doctor?->is_verified) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can download the attachment.
     */
    public function download(User $user, ConsultationAttachment $attachment): bool
    {
        $consultation = $attachment->consultation;

        // Log file download for audit
        activity('file_access')
            ->causedBy($user)
            ->performedOn($attachment)
            ->withProperties([
                'file_name' => $attachment->file_name,
                'consultation_id' => $consultation->id,
                'action' => 'downloaded'
            ])
            ->log('File attachment downloaded');

        // Users can download attachments from consultations they're part of
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return true;
        }

        if ($user->hasRole('Doctor') && $user->doctor?->id === $consultation->doctor_id) {
            return true;
        }

        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ConsultationAttachment $attachment): bool
    {
        $consultation = $attachment->consultation;

        // Log file deletion attempt for audit
        activity('file_access')
            ->causedBy($user)
            ->performedOn($attachment)
            ->withProperties([
                'file_name' => $attachment->file_name,
                'consultation_id' => $consultation->id,
                'action' => 'delete_attempted'
            ])
            ->log('File attachment deletion attempted');

        // Users can delete their own attachments within 1 hour
        if ($user->id === $attachment->user_id &&
            $attachment->created_at->diffInHours(now()) <= 1) {
            return true;
        }

        // Admins and clinic managers can delete any attachment
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ConsultationAttachment $attachment): bool
    {
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ConsultationAttachment $attachment): bool
    {
        return $user->hasRole('Admin');
    }
}
