<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClinicResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'website' => $this->website,
            'cover_url' => $this->getMediaUrl('cover'),
            'operating_hours' => $this->operating_hours,
            'commission_rate' => $this->commission_rate,
            'is_verified' => !is_null($this->verified_at),
            'verified_at' => $this->verified_at?->toISOString(),
            'is_promoted' => $this->is_promoted ?? false,
            'rating_avg' => round($this->rating_avg ?? $this->rating ?? 0, 1),
            'rating_count' => $this->rating_count ?? $this->total_reviews ?? 0,
            
            // Only include PII for authenticated users
            'phone' => $this->when(!$isGuest, $this->phone),
            'email' => $this->when(!$isGuest, $this->email),
            
            'location' => $this->when($this->latitude && $this->longitude, [
                'latitude' => (float) $this->latitude,
                'longitude' => (float) $this->longitude,
                'full_address' => $this->full_address,
            ]),
            'is_favorite' => $this->when($user && $user->hasRole('Patient'), 
                $this->isFavoriteBy($user)
            ),
            'doctors' => $this->whenLoaded('doctors', function () use ($isGuest) {
                return $this->doctors->map(function ($doctor) use ($isGuest) {
                    return [
                        'id' => $doctor->id,
                        'name' => $doctor->user?->name,
                        'specialty' => $doctor->specialty,
                        'avatar_url' => $this->getMediaUrl('avatar', $doctor),
                        'rating_avg' => round($doctor->rating_avg ?? $doctor->rating ?? 0, 1),
                        'rating_count' => $doctor->rating_count ?? $doctor->total_reviews ?? 0,
                        'role' => $doctor->pivot->role ?? null,
                        'years_experience' => $doctor->years_experience,
                        'is_verified' => !is_null($doctor->verified_at),
                        'is_promoted' => $doctor->is_promoted ?? false,
                        
                        // Only include PII for authenticated users
                        'email' => $this->when(!$isGuest, $doctor->user?->email),
                        'phone' => $this->when(!$isGuest, $doctor->user?->phone),
                        'license_number' => $this->when(!$isGuest, $doctor->license_number),
                    ];
                });
            }),
            'doctors_count' => $this->doctors_count ?? $this->doctors()->count(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Check if this clinic is favorited by the given user
     */
    private function isFavoriteBy($user): bool
    {
        if (!$user || !$user->hasRole('Patient')) {
            return false;
        }

        return \DB::table('favorite_clinics')
            ->where('patient_id', $user->id)
            ->where('clinic_id', $this->id)
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