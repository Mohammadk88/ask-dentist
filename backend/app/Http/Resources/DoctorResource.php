<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DoctorResource extends JsonResource
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
            'name' => $this->user?->name,
            'bio' => $this->bio,
            'specialty' => $this->specialty,
            'avatar_url' => $this->getMediaUrl('avatar'),
            'cover_url' => $this->getMediaUrl('cover'),
            'rating_avg' => round($this->rating_avg ?? $this->rating ?? 0, 1),
            'rating_count' => $this->rating_count ?? $this->total_reviews ?? 0,
            'years_experience' => $this->years_experience,
            'languages' => $this->languages ?? [],
            'qualifications' => $this->qualifications ?? [],
            'accepts_emergency' => $this->accepts_emergency,
            'is_verified' => !is_null($this->verified_at),
            'verified_at' => $this->verified_at?->toISOString(),
            'is_promoted' => $this->is_promoted ?? false,
            
            // Only include PII for authenticated users
            'license_number' => $this->when(!$isGuest, $this->license_number),
            'email' => $this->when(!$isGuest, $this->user?->email),
            'phone' => $this->when(!$isGuest, $this->user?->phone),
            
            'is_favorite' => $this->when($user && $user->hasRole('Patient'), 
                $this->isFavoriteBy($user)
            ),
            'clinics' => $this->whenLoaded('clinics', function () use ($isGuest) {
                return $this->clinics->map(function ($clinic) use ($isGuest) {
                    return [
                        'id' => $clinic->id,
                        'name' => $clinic->name,
                        'address' => $clinic->address,
                        'city' => $clinic->city,
                        'country' => $clinic->country,
                        'cover_url' => $this->getMediaUrl('cover', $clinic),
                        'role' => $clinic->pivot->role ?? null,
                        'is_promoted' => $clinic->is_promoted ?? false,
                        'is_verified' => !is_null($clinic->verified_at),
                        'rating_avg' => round($clinic->rating_avg ?? $clinic->rating ?? 0, 1),
                        'rating_count' => $clinic->rating_count ?? $clinic->total_reviews ?? 0,
                        
                        // Only include PII for authenticated users
                        'phone' => $this->when(!$isGuest, $clinic->phone),
                        'email' => $this->when(!$isGuest, $clinic->email),
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Check if this doctor is favorited by the given user
     */
    private function isFavoriteBy($user): bool
    {
        if (!$user || !$user->hasRole('Patient')) {
            return false;
        }

        return \DB::table('favorite_doctors')
            ->where('patient_id', $user->id)
            ->where('doctor_id', $this->id)
            ->exists();
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