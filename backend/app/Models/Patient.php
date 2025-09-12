<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Patient extends Model
{
    use HasFactory, HasUuids; // Temporarily removed LogsActivity

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'dental_history',
        'insurance_provider',
        'insurance_number',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'medical_history' => 'array',
        'dental_history' => 'array',
    ];

    /**
     * Activity log configuration - temporarily disabled
     */
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logFillable()
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }

    /**
     * Get the user that owns the patient profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get consultations created by this patient
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Get completed consultations for this patient
     */
    public function completedConsultations()
    {
        return $this->hasMany(Consultation::class)->where('status', 'completed');
    }

    /**
     * Get active consultations for this patient
     */
    public function activeConsultations()
    {
        return $this->hasMany(Consultation::class)->whereIn('status', ['pending', 'assigned', 'in_progress']);
    }

    /**
     * Get patient's age based on date of birth
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Check if patient has given treatment consent
     */
    public function hasGivenConsent(): bool
    {
        return $this->consent_treatment && $this->consent_data_sharing;
    }

    /**
     * Scope for patients with active consultations
     */
    public function scopeWithActiveConsultations($query)
    {
        return $query->whereHas('consultations', function ($q) {
            $q->whereIn('status', ['pending', 'assigned', 'in_progress']);
        });
    }

    /**
     * Get patient's consultation history count
     */
    public function getConsultationCountAttribute(): int
    {
        return $this->consultations()->count();
    }
}
