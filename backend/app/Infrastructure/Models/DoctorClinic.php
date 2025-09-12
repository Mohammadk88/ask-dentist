<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Infrastructure Model: DoctorClinic
 *
 * This model represents the persistence layer for doctor-clinic relationships
 * following hexagonal architecture principles.
 */
class DoctorClinic extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'role',
        'schedule',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'schedule' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected $dates = [
        'started_at',
        'ended_at',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at');
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeCurrentlyActive($query)
    {
        return $query->where('started_at', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('ended_at')
                          ->orWhere('ended_at', '>', now());
                    });
    }

    // Accessors
    public function getIsActiveAttribute(): bool
    {
        return is_null($this->ended_at);
    }

    public function getIsCurrentlyActiveAttribute(): bool
    {
        return $this->started_at <= now() &&
               (is_null($this->ended_at) || $this->ended_at > now());
    }
}
