<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\MediaService;

class Story extends Model
{
    use HasFactory;
    protected $fillable = [
        'owner_type',
        'owner_id',
        'media',
        'caption',
        'lang',
        'starts_at',
        'expires_at',
        'is_ad'
    ];

    protected $casts = [
        'media' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_ad' => 'boolean'
    ];

    /**
     * Get the owning model (doctor or clinic)
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if story is active (within time range)
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->starts_at <= $now && $this->expires_at > $now;
    }

    /**
     * Get media URLs with signed URLs for each media item
     */
    public function getMediaUrlsAttribute(): array
    {
        if (!$this->media || !is_array($this->media)) {
            return [];
        }

        $mediaService = app(MediaService::class);
        $urls = [];

        foreach ($this->media as $index => $mediaItem) {
            if (isset($mediaItem['path'])) {
                $urls[] = [
                    'url' => $mediaService->generateSignedUrl('story', $this->id, ['index' => $index]),
                    'type' => $mediaItem['type'] ?? 'image',
                    'caption' => $mediaItem['caption'] ?? null,
                ];
            }
        }

        return $urls;
    }

    /**
     * Get the first media item URL
     */
    public function getFirstMediaUrlAttribute(): ?string
    {
        $mediaUrls = $this->media_urls;
        return $mediaUrls[0]['url'] ?? null;
    }

    /**
     * Scope for active stories
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('starts_at', '<=', $now)
                    ->where('expires_at', '>', $now);
    }

    /**
     * Scope for stories by locale
     */
    public function scopeByLocale($query, string $locale)
    {
        return $query->where('lang', $locale);
    }

    /**
     * Scope for ad stories
     */
    public function scopeAds($query)
    {
        return $query->where('is_ad', true);
    }

    /**
     * Scope for organic (non-ad) stories
     */
    public function scopeOrganic($query)
    {
        return $query->where('is_ad', false);
    }
}
