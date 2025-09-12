<?php

namespace App\Http\Resources\Traits;

use Illuminate\Support\Facades\Storage;

trait HandlesSecureMedia
{
    /**
     * Get media URL with proper handling for public/private content
     */
    protected function getMediaUrl(string $type, $model = null): ?string
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
    protected function isPublicMedia(string $path): bool
    {
        // Avatar and cover photos are typically public
        // Private media would be things like documents, medical photos, etc.
        return str_contains($path, 'avatars/') || 
               str_contains($path, 'covers/') || 
               str_contains($path, 'public/');
    }

    /**
     * Check if user is a guest based on request attributes
     */
    protected function isGuest($request): bool
    {
        return $request->attributes->get('is_guest', !auth()->check());
    }

    /**
     * Get standardized doctor data for resources with PII protection
     */
    protected function getDoctorData($doctor, bool $isGuest): array
    {
        return [
            'id' => $doctor->id,
            'name' => $doctor->user?->name,
            'specialty' => $doctor->specialty,
            'avatar_url' => $this->getMediaUrl('avatar', $doctor),
            'rating_avg' => round($doctor->rating_avg ?? $doctor->rating ?? 0, 1),
            'rating_count' => $doctor->rating_count ?? $doctor->total_reviews ?? 0,
            'years_experience' => $doctor->years_experience,
            'is_verified' => !is_null($doctor->verified_at),
            'is_promoted' => $doctor->is_promoted ?? false,
            
            // Only include PII for authenticated users
            'email' => $this->when(!$isGuest, $doctor->user?->email),
            'phone' => $this->when(!$isGuest, $doctor->user?->phone),
            'license_number' => $this->when(!$isGuest, $doctor->license_number),
        ];
    }

    /**
     * Get standardized clinic data for resources with PII protection
     */
    protected function getClinicData($clinic, bool $isGuest): array
    {
        return [
            'id' => $clinic->id,
            'name' => $clinic->name,
            'address' => $clinic->address,
            'city' => $clinic->city,
            'country' => $clinic->country,
            'cover_url' => $this->getMediaUrl('cover', $clinic),
            'is_verified' => !is_null($clinic->verified_at),
            'is_promoted' => $clinic->is_promoted ?? false,
            'rating_avg' => round($clinic->rating_avg ?? $clinic->rating ?? 0, 1),
            'rating_count' => $clinic->rating_count ?? $clinic->total_reviews ?? 0,
            
            // Only include PII for authenticated users
            'phone' => $this->when(!$isGuest, $clinic->phone),
            'email' => $this->when(!$isGuest, $clinic->email),
        ];
    }
}