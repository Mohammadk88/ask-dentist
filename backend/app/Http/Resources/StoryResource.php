<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        $isGuest = $request->attributes->get('is_guest', !auth()->check());
        
        return [
            'id' => $this->id,
            'caption' => $this->caption,
            'media_urls' => $this->getStoryMediaUrls(),
            'first_media_url' => $this->getStoryMediaUrls()[0] ?? null, // For backwards compatibility
            'lang' => $this->lang,
            'starts_at' => $this->starts_at?->toISOString(),
            'expires_at' => $this->expires_at?->toISOString(),
            'is_ad' => $this->is_ad,
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at?->toISOString(),
            'owner' => $this->whenLoaded('owner', function () use ($isGuest) {
                return [
                    'id' => $this->owner->id,
                    'type' => class_basename($this->owner),
                    'name' => $this->owner->name ?? $this->owner->user?->name,
                    'avatar_url' => $this->getMediaUrl('avatar', $this->owner),
                    
                    // Only include PII for authenticated users
                    'email' => $this->when(!$isGuest && isset($this->owner->email), $this->owner->email),
                    'phone' => $this->when(!$isGuest && isset($this->owner->phone), $this->owner->phone),
                ];
            }),
            'doctor' => $this->whenLoaded('doctor', function () use ($isGuest) {
                return [
                    'id' => $this->doctor->id,
                    'name' => $this->doctor->user?->name,
                    'specialty' => $this->doctor->specialty,
                    'avatar_url' => $this->getMediaUrl('avatar', $this->doctor),
                    'rating_avg' => round($this->doctor->rating_avg ?? $this->doctor->rating ?? 0, 1),
                    'rating_count' => $this->doctor->rating_count ?? $this->doctor->total_reviews ?? 0,
                    'is_verified' => !is_null($this->doctor->verified_at),
                    'is_promoted' => $this->doctor->is_promoted ?? false,
                    
                    // Only include PII for authenticated users
                    'email' => $this->when(!$isGuest, $this->doctor->user?->email),
                    'phone' => $this->when(!$isGuest, $this->doctor->user?->phone),
                    'license_number' => $this->when(!$isGuest, $this->doctor->license_number),
                ];
            }),
            'clinic' => $this->whenLoaded('clinic', function () use ($isGuest) {
                return [
                    'id' => $this->clinic->id,
                    'name' => $this->clinic->name,
                    'cover_url' => $this->getMediaUrl('cover', $this->clinic),
                    'is_verified' => !is_null($this->clinic->verified_at),
                    'is_promoted' => $this->clinic->is_promoted ?? false,
                    'rating_avg' => round($this->clinic->rating_avg ?? $this->clinic->rating ?? 0, 1),
                    'rating_count' => $this->clinic->rating_count ?? $this->clinic->total_reviews ?? 0,
                    
                    // Only include PII for authenticated users
                    'phone' => $this->when(!$isGuest, $this->clinic->phone),
                    'email' => $this->when(!$isGuest, $this->clinic->email),
                ];
            }),
        ];
    }

    /**
     * Get story media URLs with proper security handling
     */
    private function getStoryMediaUrls(): array
    {
        $media = is_array($this->media) ? $this->media : json_decode($this->media, true);
        
        if (!$media) {
            return [];
        }

        $urls = [];
        foreach ($media as $index => $mediaItem) {
            if (isset($mediaItem['path'])) {
                // Stories are typically private content
                $mediaId = 'story_' . $this->id;
                $urls[] = \URL::temporarySignedRoute(
                    'api.media.signed',
                    now()->addMinutes(10),
                    ['id' => $mediaId, 'index' => $index]
                );
            }
        }

        return $urls;
    }

    /**
     * Get media URL with proper handling for public/private content
     */
    private function getMediaUrl(string $type, $model = null): ?string
    {
        $model = $model ?? $this;
        $path = $type === 'avatar' ? $model->avatar_path : $model->cover_path;
        
        if (!$path) {
            return null;
        }
        
        // Check if media is public or private
        if ($this->isPublicMedia($path)) {
            return Storage::url($path);
        } else {
            // Generate temporary signed URL for private media (10 minutes)
            $mediaId = $type . '_' . $model->id;
            return \URL::temporarySignedRoute(
                'api.media.signed',
                now()->addMinutes(10),
                ['id' => $mediaId, 'type' => $type]
            );
        }
    }

    /**
     * Determine if media should be public or private
     */
    private function isPublicMedia(string $path): bool
    {
        // Avatar and cover photos are typically public
        // Private media would be things like documents, medical photos, etc.
        return str_contains($path, 'avatars/') || 
               str_contains($path, 'covers/') || 
               str_contains($path, 'public/');
    }
}