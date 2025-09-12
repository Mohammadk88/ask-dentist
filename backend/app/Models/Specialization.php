<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Specialization extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
     * Get doctors with this specialization
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * Get consultations for this specialization
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Scope for active specializations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specializations with available doctors
     */
    public function scopeWithAvailableDoctors($query)
    {
        return $query->whereHas('doctors', function ($q) {
            $q->where('is_available', true)
              ->where('is_verified', true);
        });
    }

    /**
     * Get available doctors count for this specialization
     */
    public function getAvailableDoctorsCountAttribute(): int
    {
        return $this->doctors()
            ->where('is_available', true)
            ->where('is_verified', true)
            ->count();
    }

    /**
     * Get total consultations count for this specialization
     */
    public function getConsultationsCountAttribute(): int
    {
        return $this->consultations()->count();
    }

    /**
     * Get pending consultations count for this specialization
     */
    public function getPendingConsultationsCountAttribute(): int
    {
        return $this->consultations()
            ->where('status', 'pending')
            ->count();
    }
}
