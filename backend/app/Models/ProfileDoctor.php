<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProfileDoctor extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'profiles_doctor';

    protected $fillable = [
        'user_id',
        'clinic_id',
        'specialty',
        'bio',
        'licenses_json',
        'rating',
        'response_time_sec',
        'active_patients_count',
    ];

    protected $casts = [
        'licenses_json' => 'array',
        'rating' => 'decimal:2',
        'response_time_sec' => 'integer',
        'active_patients_count' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    // Scopes
    public function scopeBySpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    public function scopeWithHighRating($query, float $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeFastResponse($query, int $maxSeconds)
    {
        return $query->where('response_time_sec', '<=', $maxSeconds);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['specialty', 'bio', 'rating', 'response_time_sec'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
