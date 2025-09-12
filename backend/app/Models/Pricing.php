<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pricing extends Model
{
    use HasFactory, HasUuids; // Removed LogsActivity

    protected $table = 'pricing';

    protected $fillable = [
        'clinic_id',
        'service_id',
        'base_price',
        'currency',
        'discount_percentage',
        'valid_from',
        'valid_until',
        'conditions',
        'is_negotiable',
        'notes',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'conditions' => 'array',
        'is_negotiable' => 'boolean',
    ];

    // Relationships
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('valid_from', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('valid_until')
                          ->orWhere('valid_until', '>=', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now());
    }

    public function scopeForClinic($query, $clinicId)
    {
        return $query->where('clinic_id', $clinicId);
    }

    public function scopeForService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    // Accessors
    public function getDiscountedPriceAttribute(): float
    {
        return $this->base_price * (1 - $this->discount_percentage / 100);
    }

    public function getFormattedPriceAttribute(): string
    {
        $price = $this->discount_percentage > 0 ? $this->discounted_price : $this->base_price;
        return number_format($price, 2) . ' ' . $this->currency;
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->valid_from <= now() && 
               ($this->valid_until === null || $this->valid_until >= now());
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->valid_until !== null && $this->valid_until < now();
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_expired) {
            return 'expired';
        }
        
        if ($this->is_active) {
            return 'active';
        }
        
        return 'scheduled';
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
