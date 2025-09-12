<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Consultation model - maps to treatment_requests table
 * This provides backwards compatibility for the chat system
 */
class Consultation extends Model
{
    use HasFactory, LogsActivity;

    // Use the treatment_requests table
    protected $table = 'treatment_requests';

    protected $fillable = [
        'patient_id',
        'case_type',
        'message',
        'images_json',
        'status',
    ];

    protected $casts = [
        'images_json' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ASSIGNED = 'assigned';  
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the patient that owns the consultation
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the assigned doctor through treatment plans or request doctors
     */
    public function doctor()
    {
        // Try to get doctor from accepted treatment plan first
        $acceptedPlan = $this->treatmentPlans()->where('status', 'accepted')->first();
        if ($acceptedPlan) {
            return $acceptedPlan->doctor();
        }

        // Otherwise get from assigned doctors
        return $this->assignedDoctors()->first();
    }

    /**
     * Get assigned doctors through treatment request doctors
     */
    public function assignedDoctors()
    {
        return $this->belongsToMany(Doctor::class, 'treatment_request_doctors', 'request_id', 'doctor_id');
    }

    /**
     * Get treatment plans for this request
     */
    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class, 'request_id');
    }

    /**
     * Get messages for this consultation/treatment request
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'request_id');
    }

    /**
     * Get unread messages for this consultation
     */
    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'request_id')->whereNull('read_at');
    }

    /**
     * Scope for pending consultations
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for assigned consultations
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', self::STATUS_ASSIGNED);
    }

    /**
     * Scope for in progress consultations
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope for completed consultations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for cancelled consultations
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Check if consultation is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if consultation is assigned
     */
    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    /**
     * Check if consultation is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if consultation is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if consultation is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}