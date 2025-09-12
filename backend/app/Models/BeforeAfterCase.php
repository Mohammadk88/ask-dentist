<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MediaService;

class BeforeAfterCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'title',
        'description',
        'before_path',
        'after_path',
        'tags',
        'treatment_type',
        'duration_days',
        'procedure_details',
        'cost_range',
        'is_featured',
        'status',
        'is_approved',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'duration_days' => 'integer',
    ];

    /**
     * Get the doctor that owns the case
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the clinic associated with the case
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Scope to get published cases
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('is_approved', true);
    }

    /**
     * Scope to get featured cases
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get signed URL for before image
     */
    public function getBeforeUrlAttribute(): ?string
    {
        if (!$this->before_path) {
            return null;
        }

        $mediaService = app(MediaService::class);
        return $mediaService->generateSignedUrl('before_after', $this->id, ['type' => 'before']);
    }

    /**
     * Get signed URL for after image
     */
    public function getAfterUrlAttribute(): ?string
    {
        if (!$this->after_path) {
            return null;
        }

        $mediaService = app(MediaService::class);
        return $mediaService->generateSignedUrl('before_after', $this->id, ['type' => 'after']);
    }

    /**
     * Get both before and after URLs
     */
    public function getMediaUrlsAttribute(): array
    {
        return [
            'before_url' => $this->before_url,
            'after_url' => $this->after_url,
        ];
    }
}
