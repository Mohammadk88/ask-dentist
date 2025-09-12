<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Consultation;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*
 * Private user channels for personal notifications
 */
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

/*
 * Private conversation channels for real-time messaging
 */
Broadcast::channel('conversation.{consultationId}', function ($user, $consultationId) {
    $consultation = Consultation::find($consultationId);

    if (!$consultation) {
        return false;
    }

    // Allow access if user is the patient or the assigned doctor
    if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'patient'];
    }

    if ($user->hasRole('Doctor') && $user->doctor?->id === $consultation->doctor_id) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'doctor'];
    }

    // Allow admin and clinic manager access
    if ($user->hasRole(['Admin', 'ClinicManager'])) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'admin'];
    }

    return false;
});

/*
 * Legacy consultation channels for backwards compatibility
 */
Broadcast::channel('consultation.{consultationId}', function ($user, $consultationId) {
    $consultation = Consultation::find($consultationId);

    if (!$consultation) {
        return false;
    }

    // Allow access if user is the patient or the assigned doctor
    if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'patient'];
    }

    if ($user->hasRole('Doctor') && $user->doctor?->id === $consultation->doctor_id) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'doctor'];
    }

    // Allow admin and clinic manager access
    if ($user->hasRole(['Admin', 'ClinicManager'])) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'admin'];
    }

    return false;
});

/*
 * Doctor availability channel for real-time status updates
 */
Broadcast::channel('doctor.{doctorId}.availability', function ($user, $doctorId) {
    // Only the doctor themselves can listen to their availability channel
    if ($user->hasRole('Doctor') && $user->doctor?->id == $doctorId) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    // Admin and clinic managers can monitor all doctors
    if ($user->hasRole(['Admin', 'ClinicManager'])) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'admin'];
    }

    return false;
});

/*
 * Global notifications channel for system-wide alerts
 */
Broadcast::channel('notifications.global', function ($user) {
    // All authenticated users can receive global notifications
    return $user ? ['id' => $user->id, 'name' => $user->name] : false;
});

/*
 * Role-specific notification channels
 */
Broadcast::channel('notifications.doctors', function ($user) {
    return $user->hasRole('Doctor') ? ['id' => $user->id, 'name' => $user->name] : false;
});

Broadcast::channel('notifications.patients', function ($user) {
    return $user->hasRole('Patient') ? ['id' => $user->id, 'name' => $user->name] : false;
});

Broadcast::channel('notifications.admins', function ($user) {
    return $user->hasRole(['Admin', 'ClinicManager']) ? ['id' => $user->id, 'name' => $user->name] : false;
});

/*
 * Emergency consultation channel for urgent cases
 */
Broadcast::channel('emergency.consultations', function ($user) {
    // Only verified doctors and admins can listen to emergency consultations
    if ($user->hasRole('Doctor') && $user->doctor?->is_verified) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'doctor'];
    }

    if ($user->hasRole(['Admin', 'ClinicManager'])) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'admin'];
    }

    return false;
});
