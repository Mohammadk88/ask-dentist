<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\ClinicResource;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\BeforeAfterResource;
use App\Repositories\Contracts\StoryRepositoryInterface;
use App\Repositories\Contracts\ClinicRepositoryInterface;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\FavoritesRepositoryInterface;
use App\Repositories\Contracts\BeforeAfterRepositoryInterface;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected $storyRepository;
    protected $clinicRepository;
    protected $doctorRepository;
    protected $favoritesRepository;
    protected $beforeAfterRepository;

    public function __construct(
        StoryRepositoryInterface $storyRepository,
        ClinicRepositoryInterface $clinicRepository,
        DoctorRepositoryInterface $doctorRepository,
        FavoritesRepositoryInterface $favoritesRepository,
        BeforeAfterRepositoryInterface $beforeAfterRepository
    ) {
        $this->storyRepository = $storyRepository;
        $this->clinicRepository = $clinicRepository;
        $this->doctorRepository = $doctorRepository;
        $this->favoritesRepository = $favoritesRepository;
        $this->beforeAfterRepository = $beforeAfterRepository;

        // Apply optional authentication to allow guest browsing
        $this->middleware('opt.auth');

        // Add throttle middleware for public endpoints
        $this->middleware('throttle:60,1')->only([
            'index',
            'getStories',
            'getClinics',
            'getDoctors',
            'getBeforeAfter'
        ]);

        // Require authentication only for favorites operations
        $this->middleware('auth:api')->only([
            'toggleFavoriteDoctor',
            'toggleFavoriteClinic',
            'listFavoriteDoctors',
            'listFavoriteClinics',
            'getAllFavorites'
        ]);
    }
    /**
     * Get home feed data with locale-based caching
     * Supports both authenticated users and guests
     */
    public function index(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        // Store device ID for analytics (future use)
        $deviceId = $request->header('X-Device-Id');
        if ($deviceId) {
            $request->attributes->set('device_id', $deviceId);
        }

        // Detect guest status
        $isGuest = $request->attributes->get('is_guest', !auth()->check());
        $user = auth()->user();
        $isPatient = $user && $user->role === 'patient';

        // Sample data for testing UI components
        $sampleStories = [
            [
                'id' => 1,
                'title' => 'نصائح للعناية بالأسنان',
                'content' => 'اكتشف أفضل الطرق للحفاظ على صحة أسنانك',
                'image' => 'https://picsum.photos/300/200?random=1',
                'created_at' => now()->subDays(2)->toISOString(),
                'likes_count' => 45
            ],
            [
                'id' => 2,
                'title' => 'تقنيات التبييض الحديثة',
                'content' => 'آخر التطورات في تبييض الأسنان',
                'image' => 'https://picsum.photos/300/200?random=2',
                'created_at' => now()->subDays(1)->toISOString(),
                'likes_count' => 32
            ],
            [
                'id' => 3,
                'title' => 'زراعة الأسنان الرقمية',
                'content' => 'كيف غيرت التكنولوجيا عالم زراعة الأسنان',
                'image' => 'https://picsum.photos/300/200?random=3',
                'created_at' => now()->subHours(12)->toISOString(),
                'likes_count' => 67
            ]
        ];

        $sampleDoctors = [
            [
                'id' => 1,
                'name' => 'د. أحمد محمد',
                'specialty' => 'تقويم الأسنان',
                'rating' => 4.8,
                'reviews_count' => 124,
                'image' => 'https://picsum.photos/150/150?random=4',
                'years_experience' => 15,
                'clinic_name' => 'عيادة الأسنان المتطورة'
            ],
            [
                'id' => 2,
                'name' => 'د. فاطمة علي',
                'specialty' => 'تجميل الأسنان',
                'rating' => 4.9,
                'reviews_count' => 89,
                'image' => 'https://picsum.photos/150/150?random=5',
                'years_experience' => 12,
                'clinic_name' => 'مركز الابتسامة الجميلة'
            ],
            [
                'id' => 3,
                'name' => 'د. خالد سعد',
                'specialty' => 'جراحة الفم والأسنان',
                'rating' => 4.7,
                'reviews_count' => 156,
                'image' => 'https://picsum.photos/150/150?random=6',
                'years_experience' => 18,
                'clinic_name' => 'مستشفى الأسنان الملكي'
            ]
        ];

        $sampleClinics = [
            [
                'id' => 1,
                'name' => 'عيادة الأسنان المتطورة',
                'location' => 'الرياض - حي الملك فهد',
                'rating' => 4.6,
                'reviews_count' => 234,
                'image' => 'https://picsum.photos/200/150?random=7',
                'specialties' => ['تقويم الأسنان', 'تبييض', 'زراعة']
            ],
            [
                'id' => 2,
                'name' => 'مركز الابتسامة الجميلة',
                'location' => 'جدة - حي الزهراء',
                'rating' => 4.8,
                'reviews_count' => 167,
                'image' => 'https://picsum.photos/200/150?random=8',
                'specialties' => ['تجميل الأسنان', 'فينير', 'تبييض']
            ]
        ];

        $sampleBeforeAfter = [
            [
                'id' => 1,
                'title' => 'تقويم الأسنان المعقد',
                'before_image' => 'https://picsum.photos/250/200?random=9',
                'after_image' => 'https://picsum.photos/250/200?random=10',
                'doctor_name' => 'د. أحمد محمد',
                'treatment_duration' => '18 شهر',
                'description' => 'حالة تقويم معقدة تم علاجها بنجاح'
            ],
            [
                'id' => 2,
                'title' => 'تبييض وتجميل شامل',
                'before_image' => 'https://picsum.photos/250/200?random=11',
                'after_image' => 'https://picsum.photos/250/200?random=12',
                'doctor_name' => 'د. فاطمة علي',
                'treatment_duration' => '3 أسابيع',
                'description' => 'تبييض وتجميل شامل للأسنان'
            ],
            [
                'id' => 3,
                'title' => 'زراعة أسنان كاملة',
                'before_image' => 'https://picsum.photos/250/200?random=13',
                'after_image' => 'https://picsum.photos/250/200?random=14',
                'doctor_name' => 'د. خالد سعد',
                'treatment_duration' => '6 أشهر',
                'description' => 'زراعة أسنان كاملة بالتقنيات الحديثة'
            ]
        ];

        $response = [
            'success' => true,
            'message' => 'Welcome to Ask Dentist API',
            'data' => [
                'stories' => $sampleStories,
                'top_clinics' => $sampleClinics,
                'top_doctors' => $sampleDoctors,
                'before_after' => $sampleBeforeAfter,
            ],
            'action_flags' => [
                'can_favorite' => auth()->check(),
                'can_book' => auth()->check(),
                'can_message' => auth()->check()
            ],
            'locale' => $locale,
            'timestamp' => now()->toISOString(),
            'is_guest' => $isGuest,
            'user_authenticated' => !$isGuest
        ];

        return response()->json($response);
    }

    /**
     * Get stories endpoint
     */
    public function getStories(Request $request)
    {
        // Store device ID for analytics (future use)
        $deviceId = $request->header('X-Device-Id');
        if ($deviceId) {
            $request->attributes->set('device_id', $deviceId);
        }

        $locale = $request->header('Accept-Language', 'en');
        $stories = $this->storyRepository->fetchActive($locale, 20);

        return StoryResource::collection($stories);
    }

    /**
     * Get clinics endpoint
     */
    public function getClinics(Request $request)
    {
        // Store device ID for analytics (future use)
        $deviceId = $request->header('X-Device-Id');
        if ($deviceId) {
            $request->attributes->set('device_id', $deviceId);
        }

        $clinics = $this->clinicRepository->top(10);

        return ClinicResource::collection($clinics);
    }

    /**
     * Get doctors endpoint
     */
    public function getDoctors(Request $request)
    {
        // Store device ID for analytics (future use)
        $deviceId = $request->header('X-Device-Id');
        if ($deviceId) {
            $request->attributes->set('device_id', $deviceId);
        }

        $doctors = $this->doctorRepository->top(15);

        return DoctorResource::collection($doctors);
    }

    /**
     * Get before/after cases endpoint
     */
    public function getBeforeAfter(Request $request)
    {
        // Store device ID for analytics (future use)
        $deviceId = $request->header('X-Device-Id');
        if ($deviceId) {
            $request->attributes->set('device_id', $deviceId);
        }

        $cases = $this->beforeAfterRepository->getPublished(12);

        return BeforeAfterResource::collection($cases);
    }

    /**
     * Toggle doctor favorite status
     */
    public function toggleFavoriteDoctor(Request $request, Doctor $doctor)
    {
        $this->authorize('manageFavorites', Doctor::class);

        $user = Auth::user();
        $isFavorite = $this->favoritesRepository->toggleFavoriteDoctor($user->id, $doctor->id);

        return response()->json([
            'is_favorite' => $isFavorite,
            'message' => $isFavorite ? 'Doctor added to favorites' : 'Doctor removed from favorites'
        ]);
    }

    /**
     * Toggle clinic favorite status
     */
    public function toggleFavoriteClinic(Request $request, Clinic $clinic)
    {
        $this->authorize('manageFavorites', Clinic::class);

        $user = Auth::user();
        $isFavorite = $this->favoritesRepository->toggleFavoriteClinic($user->id, $clinic->id);

        return response()->json([
            'is_favorite' => $isFavorite,
            'message' => $isFavorite ? 'Clinic added to favorites' : 'Clinic removed from favorites'
        ]);
    }

    /**
     * Get user's favorite doctors endpoint
     */
    public function listFavoriteDoctors(Request $request)
    {
        $this->authorize('manageFavorites', Doctor::class);

        $user = Auth::user();
        $favorites = $this->favoritesRepository->getFavoriteDoctors($user->id);

        return response()->json([
            'data' => DoctorResource::collection($favorites),
            'count' => $favorites->count()
        ]);
    }

    /**
     * Get user's favorite clinics endpoint
     */
    public function listFavoriteClinics(Request $request)
    {
        $this->authorize('manageFavorites', Clinic::class);

        $user = Auth::user();
        $favorites = $this->favoritesRepository->getFavoriteClinics($user->id);

        return response()->json([
            'data' => ClinicResource::collection($favorites),
            'count' => $favorites->count()
        ]);
    }

    /**
     * Get all user favorites (combined)
     */
    public function getAllFavorites(Request $request)
    {
        $this->authorize('manageFavorites', Doctor::class);

        $user = Auth::user();
        $favorites = $this->favoritesRepository->getAllFavorites($user->id);

        return response()->json([
            'doctors' => DoctorResource::collection($favorites['doctors']),
            'clinics' => ClinicResource::collection($favorites['clinics']),
            'counts' => $this->favoritesRepository->getFavoriteCounts($user->id)
        ]);
    }

    /**
     * Invalidate home cache - call this when content is updated
     */
    public function invalidateHomeCache($locale = null)
    {
        $locales = $locale ? [$locale] : ['en', 'ar']; // Add more locales as needed

        foreach ($locales as $loc) {
            // Clear guest cache
            Cache::forget("cache:home:guest:{$loc}");

            // Clear user caches - this is a simple approach
            // In production, you might want to tag caches for better management
            $pattern = "cache:home:user:*:{$loc}";
            // Note: Laravel doesn't have built-in wildcard cache clearing
            // You might want to implement cache tagging or use Redis for this
        }
    }
}
