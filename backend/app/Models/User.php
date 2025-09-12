<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasUuids;

    /**
     * The guard name for roles and permissions
     */
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'locale',
        'status',
        'fcm_tokens',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fcm_tokens' => 'array',
        ];
    }

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->dontLogFillable()
            ->dontLogUnguarded()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Check if user has verified their phone number
     */
    public function hasVerifiedPhone(): bool
    {
        return true; // Simplified for now
    }

    /**
     * Mark phone as verified
     */
    public function markPhoneAsVerified(): bool
    {
        return true; // Simplified for now
    }

    /**
     * Get verification tokens
     */
    public function verificationTokens()
    {
        return $this->hasMany(VerificationToken::class);
    }

    /**
     * Check if user is a doctor
     */
    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    /**
     * Check if user is a patient
     */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the doctor profile if user is a doctor
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the patient profile if user is a patient
     */
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Get sent messages
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get uploaded consultation attachments
     */
    public function uploadedAttachments()
    {
        return $this->hasMany(ConsultationAttachment::class, 'uploaded_by');
    }

    /**
     * Get user's favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get user's favorite doctors
     */
    public function favoriteDoctors()
    {
        return $this->morphedByMany(Doctor::class, 'favoritable', 'favorites', 'user_id', 'favoritable_id');
    }

    /**
     * Get user's favorite clinics
     */
    public function favoriteClinics()
    {
        return $this->morphedByMany(Clinic::class, 'favoritable', 'favorites', 'user_id', 'favoritable_id');
    }
}
