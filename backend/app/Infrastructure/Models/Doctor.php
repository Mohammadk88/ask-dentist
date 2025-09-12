<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Infrastructure Model: Doctor
 *
 * This model represents the persistence layer for doctor entities
 * following hexagonal architecture principles.
 */
class Doctor extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

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
    ];

    protected $casts = [
        'qualifications' => 'array',
        'languages' => 'array',
        'rating' => 'decimal:2',
        'years_experience' => 'integer',
        'total_reviews' => 'integer',
        'accepts_emergency' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected $dates = [
        'verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctorClinics(): HasMany
    {
        return $this->hasMany(DoctorClinic::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function treatmentRequestDoctors(): HasMany
    {
        return $this->hasMany(TreatmentRequestDoctor::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeBySpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    public function scopeAcceptsEmergency($query)
    {
        return $query->where('accepts_emergency', true);
    }

    public function scopeWithMinRating($query, float $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('status', 'active');
        });
    }

    public function scopeAvailableForDispatch($query)
    {
        return $query->verified()
                    ->active();
    }

    // Accessors
    public function getIsVerifiedAttribute(): bool
    {
        return !is_null($this->verified_at);
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->full_name;
    }

    // Dispatch scoring methods
    public function getActivePatientsCountAttribute(): int
    {
        return $this->treatmentPlans()
                   ->whereIn('status', ['active', 'in_progress', 'scheduled'])
                   ->count();
    }

    public function getRatingAvgAttribute(): float
    {
        return (float) $this->rating ?? 0.0;
    }

    public function getResponseTimeMs(): float
    {
        // Calculate average response time based on historical data
        // For now, return a calculated value based on experience and rating
        $baseTime = 3600000; // 1 hour in milliseconds
        $experienceFactor = max(0.1, 1 - ($this->years_experience / 20)); // Better with more experience
        $ratingFactor = max(0.1, 1 - ($this->rating_avg / 5)); // Better with higher rating
        
        return $baseTime * $experienceFactor * $ratingFactor;
    }

    public function getNewDoctorRotationScore(): int
    {
        // Count recent dispatches (last 30 days) to implement rotation
        return $this->treatmentRequestDoctors()
                   ->where('created_at', '>=', now()->subDays(30))
                   ->count();
    }

    public function calculateDispatchScore(): float
    {
        // Scoring tuple: (active_patients_count asc, rating_avg desc, response_time_ms asc, rotation asc)
        // Lower score is better for dispatch selection
        
        $activePatientsWeight = 1000000; // Highest priority
        $ratingWeight = 100000;
        $responseTimeWeight = 1000;
        $rotationWeight = 100;
        
        $score = ($this->active_patients_count * $activePatientsWeight) +
                ((5.0 - $this->rating_avg) * $ratingWeight) + // Invert rating (higher rating = lower score)
                (($this->getResponseTimeMs() / 1000) * $responseTimeWeight) + // Convert to seconds
                ($this->getNewDoctorRotationScore() * $rotationWeight);
        
        return round($score, 4);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Infrastructure\DoctorFactory::new();
    }
}
