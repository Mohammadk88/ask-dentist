<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\MediaService;

class Clinic extends Model
{
    use HasFactory, HasUuids, SoftDeletes; // Added HasUuids

    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'website',
        'country',
        'city',
        'address',
        'latitude',
        'longitude',
        'operating_hours',
        'cover_path',
        'verified_at',
        'commission_rate',
        'is_promoted',
        'promoted_until',
        'verification_notes',
        'documents_json',
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'documents_json' => 'array',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'commission_rate' => 'decimal:2',
        'verified_at' => 'datetime',
        'is_promoted' => 'boolean',
        'promoted_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function doctors(): HasMany
    {
        return $this->hasMany(ProfileDoctor::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(ClinicWorkingHour::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ClinicDocument::class);
    }

    public function pricing(): HasMany
    {
        return $this->hasMany(\App\Infrastructure\Models\Pricing::class);
    }

    // Scopes
    public function scopeByCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    public function scopeWithHighCommission($query, float $minRate)
    {
        return $query->where('commission_rate', '>=', $minRate);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeInCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->country}";
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    // Activity Log
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['name', 'country', 'city', 'commission_rate'])
    //         ->logOnlyDirty()
    //         ->dontSubmitEmptyLogs();
    // }

    /**
     * Get public cover URL
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_path) {
            return null;
        }

        $mediaService = app(MediaService::class);
        $filename = basename($this->cover_path);
        return $mediaService->generatePublicUrl('covers', $filename);
    }

    /**
     * Get all media URLs for the clinic
     */
    public function getMediaUrlsAttribute(): array
    {
        return [
            'cover_url' => $this->cover_url,
        ];
    }
}
