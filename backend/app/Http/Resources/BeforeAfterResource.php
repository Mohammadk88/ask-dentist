<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeforeAfterResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'before_url' => $this->getMediaUrl('before'),
            'after_url' => $this->getMediaUrl('after'),
            'is_featured' => $this->is_featured,
            'treatment_type' => $this->treatment_type,
            'duration_days' => $this->duration_days,
            'tags' => $this->tags ?? [],
            'procedure_details' => $this->procedure_details,
            'cost_range' => $this->cost_range,
            'status' => $this->status,
            'is_approved' => $this->is_approved,
            'doctor' => $this->whenLoaded('doctor', function () use ($isGuest) {
                return [
                    'id' => $this->doctor->id,
                    'name' => $this->doctor->user?->name,
                    'specialty' => $this->doctor->specialty,
                    'avatar_url' => $this->getMediaUrl('avatar', $this->doctor),
                    'rating_avg' => round($this->doctor->rating_avg ?? $this->doctor->rating ?? 0, 1),
                    'rating_count' => $this->doctor->rating_count ?? $this->doctor->total_reviews ?? 0,
                    'years_experience' => $this->doctor->years_experience,
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
                    'city' => $this->clinic->city,
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
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get media URL with proper handling for public/private content
     */
    private function getMediaUrl(string $type, $model = null): ?string
    {
        $model = $model ?? $this;
        
        switch ($type) {
            case 'before':
                $path = $this->before_path;
                break;
            case 'after':
                $path = $this->after_path;
                break;
            case 'avatar':
                $path = $model->avatar_path;
                break;
            case 'cover':
                $path = $model->cover_path;
                break;
            default:
                return null;
        }
        
        if (!$path) {
            return null;
        }
        
        // Before/after photos are typically private, avatars/covers might be public
        if ($type === 'before' || $type === 'after' || !$this->isPublicMedia($path)) {
            // Generate temporary signed URL for private media (10 minutes)
            $mediaId = ($type === 'before' || $type === 'after') 
                ? 'before_after_' . $this->id
                : $type . '_' . $model->id;
                
            return \URL::temporarySignedRoute(
                'api.media.signed',
                now()->addMinutes(10),
                ['id' => $mediaId, 'type' => $type]
            );
        } else {
            return Storage::url($path);
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