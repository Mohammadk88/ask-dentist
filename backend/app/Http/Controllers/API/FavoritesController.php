<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RequiresAuthentication;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FavoritesController extends Controller
{
    use RequiresAuthentication;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('custom.role:Patient');
    }

    /**
     * Toggle doctor favorite status
     */
    public function toggleDoctorFavorite(string $doctorId): JsonResponse
    {
        $this->requireRole('Patient', 'toggle_doctor_favorite');

        $user = Auth::user();
        
        // Validate doctor exists
        $doctor = Doctor::findOrFail($doctorId);
        
        // Check if already favorited
        $isFavorited = $user->favoriteDoctors()->where('doctor_id', $doctorId)->exists();
        
        if ($isFavorited) {
            // Remove from favorites
            $user->favoriteDoctors()->detach($doctorId);
            $message = 'Doctor removed from favorites';
            $isFavorite = false;
        } else {
            // Add to favorites
            $user->favoriteDoctors()->attach($doctorId, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = 'Doctor added to favorites';
            $isFavorite = true;
        }
        
        // Clear cached favorites
        $this->clearFavoritesCache($user->id);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorite' => $isFavorite,
            'doctor_id' => $doctorId,
        ]);
    }

    /**
     * Toggle clinic favorite status
     */
    public function toggleClinicFavorite(string $clinicId): JsonResponse
    {
        $this->requireRole('Patient', 'toggle_clinic_favorite');

        $user = Auth::user();
        
        // Validate clinic exists
        $clinic = Clinic::findOrFail($clinicId);
        
        // Check if already favorited
        $isFavorited = $user->favoriteClinics()->where('clinic_id', $clinicId)->exists();
        
        if ($isFavorited) {
            // Remove from favorites
            $user->favoriteClinics()->detach($clinicId);
            $message = 'Clinic removed from favorites';
            $isFavorite = false;
        } else {
            // Add to favorites
            $user->favoriteClinics()->attach($clinicId, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = 'Clinic added to favorites';
            $isFavorite = true;
        }
        
        // Clear cached favorites
        $this->clearFavoritesCache($user->id);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorite' => $isFavorite,
            'clinic_id' => $clinicId,
        ]);
    }

    /**
     * Get user's favorite doctors
     */
    public function getFavoriteDoctors(): JsonResponse
    {
        $this->requireRole('Patient', 'get_favorite_doctors');

        $user = Auth::user();
        $cacheKey = "user_favorite_doctors_{$user->id}";
        
        $favoriteDoctors = Cache::remember($cacheKey, 300, function () use ($user) {
            return $user->favoriteDoctors()
                ->with(['clinics', 'specialization'])
                ->get()
                ->map(function ($doctor) {
                    return [
                        'id' => $doctor->id,
                        'name' => $doctor->first_name . ' ' . $doctor->last_name,
                        'specialty' => $doctor->specialization?->name ?? 'General Dentistry',
                        'avatar_url' => $doctor->profile_image_url,
                        'rating' => $doctor->rating ?? 0.0,
                        'review_count' => $doctor->review_count ?? 0,
                        'experience' => $doctor->experience_years . ' years',
                        'languages' => $doctor->languages ?? [],
                        'is_online' => $doctor->is_available ?? false,
                        'clinic_name' => $doctor->clinics->first()?->name ?? '',
                        'consultation_fee' => $doctor->consultation_fee ?? 0,
                        'favorited_at' => $doctor->pivot->created_at,
                    ];
                });
        });
        
        return response()->json([
            'success' => true,
            'data' => $favoriteDoctors,
            'count' => $favoriteDoctors->count(),
        ]);
    }

    /**
     * Get user's favorite clinics
     */
    public function getFavoriteClinics(): JsonResponse
    {
        $this->requireRole('Patient', 'get_favorite_clinics');
        
        $user = Auth::user();
        $cacheKey = "user_favorite_clinics_{$user->id}";
        
        $favoriteClinics = Cache::remember($cacheKey, 300, function () use ($user) {
            return $user->favoriteClinics()
                ->get()
                ->map(function ($clinic) {
                    return [
                        'id' => $clinic->id,
                        'name' => $clinic->name,
                        'image_url' => $clinic->cover_url,
                        'rating' => $clinic->rating ?? 0.0,
                        'review_count' => $clinic->review_count ?? 0,
                        'location' => $clinic->city . ', ' . $clinic->country,
                        'is_promoted' => $clinic->is_promoted ?? false,
                        'is_verified' => !is_null($clinic->verified_at),
                        'favorited_at' => $clinic->pivot->created_at,
                    ];
                });
        });
        
        return response()->json([
            'success' => true,
            'data' => $favoriteClinics,
            'count' => $favoriteClinics->count(),
        ]);
    }

    /**
     * Clear user's favorites cache
     */
    private function clearFavoritesCache(int $userId): void
    {
        Cache::forget("user_favorite_doctors_{$userId}");
        Cache::forget("user_favorite_clinics_{$userId}");
        
        // Also clear home feed cache since it includes favorites
        Cache::forget("home_feed_en_{$userId}");
        Cache::forget("home_feed_tr_{$userId}");
        Cache::forget("home_feed_ar_{$userId}");
    }
}
