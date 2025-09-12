<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentPlan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'treatment_request_id',
        'doctor_id',
        'clinic_id',
        'title',
        'description',
        'diagnosis',
        'services',
        'total_cost',
        'currency',
        'estimated_duration_days',
        'number_of_visits',
        'timeline',
        'pre_treatment_instructions',
        'post_treatment_instructions',
        'risks_and_complications',
        'alternatives',
        'status',
        'expires_at',
        'notes',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'services' => 'array',
        'timeline' => 'array',
        'risks_and_complications' => 'array',
        'alternatives' => 'array',
        'total_cost' => 'decimal:2',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the treatment request this plan belongs to
     */
    public function treatmentRequest(): BelongsTo
    {
        return $this->belongsTo(TreatmentRequest::class);
    }

    /**
     * Get the doctor who created this plan
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the clinic this plan is for
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get appointments for this treatment plan
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Scope for accepted plans
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for pending plans
     */
    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope for cancelled plans
     */
    public function scopeCancelled($query)
    {
        return $query->whereNotNull('cancelled_at');
    }

    /**
     * Check if plan is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if plan is cancelled
     */
    public function isCancelled(): bool
    {
        return !is_null($this->cancelled_at);
    }

    /**
     * Check if plan is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Accept the treatment plan
     */
    public function accept()
    {
        $this->update([
            'status' => 'accepted'
        ]);
    }

    /**
     * Submit the treatment plan
     */
    public function submit()
    {
        $this->update([
            'status' => 'submitted'
        ]);

        // Broadcast the plan submitted event
        broadcast(new \App\Events\PlanSubmitted($this))->toOthers();
    }

    /**
     * Reject the treatment plan
     */
    public function reject(string $reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'cancellation_reason' => $reason
        ]);
    }

    /**
     * Cancel the treatment plan
     */
    public function cancel(string $reason = null)
    {
        $this->update([
            'cancelled_at' => now(),
            'cancellation_reason' => $reason
        ]);
    }
}