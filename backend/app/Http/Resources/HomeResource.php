<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stories' => StoryResource::collection($this->resource['stories'] ?? []),
            'top_clinics' => ClinicResource::collection($this->resource['top_clinics'] ?? []),
            'top_doctors' => DoctorResource::collection($this->resource['top_doctors'] ?? []),
            'favorite_doctors' => DoctorResource::collection($this->resource['favorite_doctors'] ?? []),
            'before_after' => BeforeAfterResource::collection($this->resource['before_after'] ?? []),
            'meta' => [
                'locale' => $this->resource['locale'] ?? 'en',
                'cache_expires_at' => $this->resource['cache_expires_at'] ?? null,
                'last_updated' => now()->toISOString(),
            ]
        ];
    }
}