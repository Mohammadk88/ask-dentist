<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class TreatmentRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes; // Temporarily removed LogsActivity

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
        'preferred_times' => 'array',
        'preferred_date' => 'datetime',
        'is_emergency' => 'boolean',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function requestDoctors(): HasMany
    {
        return $this->hasMany(RequestDoctor::class, 'request_id');
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class, 'request_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'request_id');
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCaseType($query, string $caseType)
    {
        return $query->where('case_type', $caseType);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Activity Log - temporarily disabled
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['case_type', 'status', 'message'])
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }
}
