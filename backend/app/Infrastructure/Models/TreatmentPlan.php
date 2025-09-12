<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Infrastructure Model: TreatmentPlan
 *
 * This model represents the persistence layer for treatment plan entities
 * following hexagonal architecture principles.
 */
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
        'cancelled_at',
        'cancellation_reason',
        'notes',
    ];

    protected $casts = [
        'services' => 'array',
        'total_cost' => 'decimal:2',
        'estimated_duration_days' => 'integer',
        'number_of_visits' => 'integer',
        'timeline' => 'array',
        'risks_and_complications' => 'array',
        'alternatives' => 'array',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected $dates = [
        'expires_at',
        'cancelled_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function treatmentRequest(): BelongsTo
    {
        return $this->belongsTo(TreatmentRequest::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['draft', 'submitted', 'accepted']);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        });
    }

    public function scopeByDoctor($query, string $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByClinic($query, string $clinicId)
    {
        return $query->where('clinic_id', $clinicId);
    }

    // Accessors
    public function getIsExpiredAttribute(): bool
    {
        return !is_null($this->expires_at) && $this->expires_at < now();
    }

    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['draft', 'submitted', 'accepted']) && !$this->is_expired;
    }

    public function getDurationWeeksAttribute(): float
    {
        return $this->estimated_duration_days / 7;
    }

    public function getAverageCostPerVisitAttribute(): float
    {
        return $this->number_of_visits > 0 ? $this->total_cost / $this->number_of_visits : 0;
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Infrastructure\TreatmentPlanFactory::new();
    }
}
