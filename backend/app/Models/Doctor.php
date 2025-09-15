<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Services\MediaService;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class Doctor extends Model
{
    use HasFactory, HasUuids;
    // Temporarily removed LogsActivity trait

    protected $fillable = [
        'user_id',
        'license_number',
        'specialty',
        'bio',
        'qualifications',
        'years_experience',
        'languages',
        'rating',
        'total_reviews',
        'accepts_emergency',
        'verified_at',
        'verification_notes',
        'avatar_path',
        'cover_path',
        'is_promoted',
        'promoted_until',
    ];

    protected $casts = [
        'qualifications' => 'array',
        'languages' => 'array',
        'accepts_emergency' => 'boolean',
        'verified_at' => 'datetime',
        'rating' => 'decimal:2',
        'is_promoted' => 'boolean',
        'promoted_until' => 'datetime',
    ];

    /**
     * Activity log configuration
     */
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logFillable()
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }

    /**
     * Get the user that owns the doctor profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get consultations assigned to this doctor
     */
    public function consultations()
    {
        return $this->belongsToMany(Consultation::class, 'treatment_request_doctors', 'doctor_id', 'treatment_request_id');
    }

    /**
     * Get the clinics where this doctor works
     */
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'doctor_clinics')
                    ->withPivot(['role', 'schedule', 'started_at', 'ended_at'])
                    ->withTimestamps();
    }

    /**
     * Get completed consultations for this doctor
     */
    public function completedConsultations()
    {
        return $this->belongsToMany(Consultation::class, 'treatment_request_doctors', 'doctor_id', 'treatment_request_id')
                    ->where('treatment_requests.status', 'completed');
    }

    /**
     * Get active consultations for this doctor
     */
    public function activeConsultations()
    {
        return $this->belongsToMany(Consultation::class, 'treatment_request_doctors', 'doctor_id', 'treatment_request_id')
                    ->whereIn('treatment_requests.status', ['assigned', 'in_progress']);
    }

    /**
     * Check if doctor is available for consultations
     */
    public function isAvailableForConsultations(): bool
    {
        return $this->verified_at && $this->user->is_active;
    }

    /**
     * Get doctor's current workload
     */
    public function getCurrentWorkload(): int
    {
        return $this->activeConsultations()->count();
    }

    /**
     * Scope for verified doctors
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope for available doctors
     */
    public function scopeAvailable($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope for doctors with specific specialty
     */
    public function scopeWithSpecialty($query, $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    /**
     * Get public avatar URL
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar_path) {
            return null;
        }

        $mediaService = app(MediaService::class);
        $filename = basename($this->avatar_path);
        return $mediaService->generatePublicUrl('avatars', $filename);
    }

    /**
     * Get public cover URL
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_path) {
            return null;
        }

        $mediaService = app(MediaService::class);
        $filename = basename($this->cover_path);
        return $mediaService->generatePublicUrl('covers', $filename);
    }

    /**
     * Get all media URLs for the doctor
     */
    public function getMediaUrlsAttribute(): array
    {
        return [
            'avatar_url' => $this->avatar_url,
            'cover_url' => $this->cover_url,
        ];
    }
}
