<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Service extends Model
{
    use HasFactory, HasUuids, SoftDeletes; // Removed LogsActivity

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'duration_minutes',
        'requires_anesthesia',
        'requires_followup',
        'is_emergency',
        'is_tooth_specific',
        'prerequisites',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'requires_anesthesia' => 'boolean',
        'requires_followup' => 'boolean',
        'is_emergency' => 'boolean',
        'is_tooth_specific' => 'boolean',
        'prerequisites' => 'array',
    ];

    // Relationships
    public function pricing(): HasMany
    {
        return $this->hasMany(Pricing::class);
    }

    // Scopes
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    public function scopeRequiresAnesthesia($query)
    {
        return $query->where('requires_anesthesia', true);
    }

    public function scopeRequiresFollowup($query)
    {
        return $query->where('requires_followup', true);
    }

    public function scopeToothSpecific($query)
    {
        return $query->where('is_tooth_specific', true);
    }

    public function scopeByDurationRange($query, int $minMinutes, int $maxMinutes)
    {
        return $query->whereBetween('duration_minutes', [$minMinutes, $maxMinutes]);
    }

    // Accessors
    public function getDurationHoursAttribute(): float
    {
        return $this->duration_minutes / 60;
    }

    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return "{$minutes}m";
    }

    public function getCategoryDisplayAttribute(): string
    {
        return match($this->category) {
            'general' => 'General Dentistry',
            'preventive' => 'Preventive Care',
            'restorative' => 'Restorative',
            'cosmetic' => 'Cosmetic',
            'orthodontic' => 'Orthodontic',
            'surgical' => 'Oral Surgery',
            'endodontic' => 'Root Canal',
            'periodontal' => 'Gum Treatment',
            'pediatric' => 'Pediatric',
            'emergency' => 'Emergency',
            default => ucfirst($this->category),
        };
    }

    // Activity Log - Disabled temporarily
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logFillable()
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }
}
