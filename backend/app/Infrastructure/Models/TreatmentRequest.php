<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Infrastructure Model: TreatmentRequest
 *
 * This model represents the persistence layer for treatment request entities
 * following hexagonal architecture principles.
 */
class TreatmentRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'title',
        'description',
        'urgency',
        'symptoms',
        'affected_teeth',
        'photos',
        'status',
        'preferred_date',
        'preferred_times',
        'is_emergency',
        'notes',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'affected_teeth' => 'array',
        'photos' => 'array',
        'preferred_date' => 'datetime',
        'preferred_times' => 'array',
        'is_emergency' => 'boolean',
    ];

    protected $dates = [
        'preferred_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function treatmentRequestDoctors(): HasMany
    {
        return $this->hasMany(TreatmentRequestDoctor::class);
    }

    public function dispatchedDoctors()
    {
        return $this->belongsToMany(Doctor::class, 'treatment_request_doctors')
                   ->withPivot(['dispatch_order', 'dispatch_score', 'status', 'notified_at', 'responded_at'])
                   ->withTimestamps();
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUrgency($query, string $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            'pending', 'reviewing', 'quote_requested', 'quoted', 'accepted', 'scheduled', 'in_progress'
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'cancelled']);
    }

    // Accessors
    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, [
            'pending', 'reviewing', 'quote_requested', 'quoted', 'accepted', 'scheduled', 'in_progress'
        ]);
    }

    public function getIsCompletedAttribute(): bool
    {
        return in_array($this->status, ['completed', 'cancelled']);
    }

    public function getUrgencyLevelAttribute(): int
    {
        return match($this->urgency) {
            'low' => 1,
            'medium' => 2,
            'high' => 3,
            'emergency' => 4,
            default => 0
        };
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Infrastructure\TreatmentRequestFactory::new();
    }
}
