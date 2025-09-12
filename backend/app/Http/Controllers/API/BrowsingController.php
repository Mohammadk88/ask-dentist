<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesGuestMode;
use App\Domain\Repositories\DoctorRepositoryInterface;
use App\Domain\Repositories\ClinicRepositoryInterface;
use App\Domain\Repositories\SpecializationRepositoryInterface;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\ClinicResource;
use App\Http\Resources\SpecializationResource;
use Illuminate\Http\Request;

class BrowsingController extends Controller
{
    use HandlesGuestMode;

    public function __construct(
        private DoctorRepositoryInterface $doctorRepository,
        private ClinicRepositoryInterface $clinicRepository,
        private SpecializationRepositoryInterface $specializationRepository
    ) {
        $this->middleware(['opt.auth']);
    }

    /**
     * Browse doctors with optional filters
     */
    public function doctors(Request $request)
    {
        $request->validate([
            'specialization_id' => 'nullable|exists:specializations,id',
            'city' => 'nullable|string|max:100',
            'min_rating' => 'nullable|numeric|min:0|max:5',
            'sort_by' => 'nullable|in:rating,experience,name',
            'sort_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50',
            'page' => 'nullable|integer|min:1',
        ]);

        $filters = $request->only([
            'specialization_id',
            'city',
            'min_rating',
            'sort_by',
            'sort_direction'
        ]);

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $doctors = $this->doctorRepository->getPaginatedWithFilters($filters, $perPage);

        return response()->json($this->addGuestContext([
            'data' => DoctorResource::collection($doctors->items()),
            'meta' => [
                'current_page' => $doctors->currentPage(),
                'last_page' => $doctors->lastPage(),
                'per_page' => $doctors->perPage(),
                'total' => $doctors->total(),
                'from' => $doctors->firstItem(),
                'to' => $doctors->lastItem(),
            ],
        ], $request));
    }

    /**
     * Browse clinics with optional filters
     */
    public function clinics(Request $request)
    {
        $request->validate([
            'city' => 'nullable|string|max:100',
            'has_doctors' => 'nullable|boolean',
            'sort_by' => 'nullable|in:name,doctors_count',
            'sort_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:50',
            'page' => 'nullable|integer|min:1',
        ]);

        $filters = $request->only([
            'city',
            'has_doctors',
            'sort_by',
            'sort_direction'
        ]);

        $perPage = $request->input('per_page', 10);

        $clinics = $this->clinicRepository->getPaginatedWithFilters($filters, $perPage);

        return response()->json($this->addGuestContext([
            'data' => ClinicResource::collection($clinics->items()),
            'meta' => [
                'current_page' => $clinics->currentPage(),
                'last_page' => $clinics->lastPage(),
                'per_page' => $clinics->perPage(),
                'total' => $clinics->total(),
                'from' => $clinics->firstItem(),
                'to' => $clinics->lastItem(),
            ],
        ], $request));
    }

    /**
     * Get all specializations
     */
    public function specializations(Request $request)
    {
        $specializations = $this->specializationRepository->getAll();

        return response()->json($this->addGuestContext([
            'data' => SpecializationResource::collection($specializations),
        ], $request));
    }

    /**
     * Search doctors and clinics
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
            'type' => 'nullable|in:doctors,clinics,all',
            'per_page' => 'nullable|integer|min:5|max:50',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = $request->input('query');
        $type = $request->input('type', 'all');
        $perPage = $request->input('per_page', 10);

        $results = [];

        if ($type === 'doctors' || $type === 'all') {
            $doctors = $this->doctorRepository->search($query, $perPage);
            $results['doctors'] = [
                'data' => DoctorResource::collection($doctors->items()),
                'meta' => [
                    'current_page' => $doctors->currentPage(),
                    'last_page' => $doctors->lastPage(),
                    'per_page' => $doctors->perPage(),
                    'total' => $doctors->total(),
                    'from' => $doctors->firstItem(),
                    'to' => $doctors->lastItem(),
                ],
            ];
        }

        if ($type === 'clinics' || $type === 'all') {
            $clinics = $this->clinicRepository->search($query, $perPage);
            $results['clinics'] = [
                'data' => ClinicResource::collection($clinics->items()),
                'meta' => [
                    'current_page' => $clinics->currentPage(),
                    'last_page' => $clinics->lastPage(),
                    'per_page' => $clinics->perPage(),
                    'total' => $clinics->total(),
                    'from' => $clinics->firstItem(),
                    'to' => $clinics->lastItem(),
                ],
            ];
        }

        return response()->json($this->addGuestContext($results, $request));
    }

    /**
     * Get individual doctor details
     */
    public function doctor(Request $request, int $id)
    {
        $doctor = $this->doctorRepository->findById($id);

        if (!$doctor) {
            return response()->json([
                'message' => 'Doctor not found'
            ], 404);
        }

        return response()->json($this->addGuestContext([
            'data' => new DoctorResource($doctor),
        ], $request));
    }

    /**
     * Get individual clinic details
     */
    public function clinic(Request $request, int $id)
    {
        $clinic = $this->clinicRepository->findById($id);

        if (!$clinic) {
            return response()->json([
                'message' => 'Clinic not found'
            ], 404);
        }

        return response()->json($this->addGuestContext([
            'data' => new ClinicResource($clinic),
        ], $request));
    }

    /**
     * Get authentication requirements for various actions
     */
    public function authRequirements(Request $request)
    {
        return response()->json($this->addGuestContext([
            'auth_required_actions' => [
                'messaging' => [
                    'required' => true,
                    'description' => 'Login required to send messages to doctors',
                ],
                'calling' => [
                    'required' => true,
                    'description' => 'Login required to make voice calls',
                ],
                'video_calls' => [
                    'required' => true,
                    'description' => 'Login required to make video calls',
                ],
                'appointments' => [
                    'required' => true,
                    'description' => 'Login required to book appointments',
                ],
                'favorites' => [
                    'required' => true,
                    'description' => 'Login required to add favorites',
                ],
                'treatment_plans' => [
                    'required' => true,
                    'description' => 'Login required to accept treatment plans',
                ],
                'treatment_requests' => [
                    'required' => true,
                    'description' => 'Login required to send treatment requests',
                ],
            ],
        ], $request));
    }

    /**
     * Get popular search terms and suggestions
     */
    public function searchSuggestions(Request $request)
    {
        // This would typically come from analytics or a search suggestions table
        $suggestions = [
            'popular_searches' => [
                'تنظيف الأسنان',
                'تقويم الأسنان',
                'زراعة الأسنان',
                'تبييض الأسنان',
                'عصب الأسنان',
                'جراحة الأسنان',
                'أسنان الأطفال',
                'طربوش الأسنان',
            ],
            'popular_specializations' => [
                'تقويم الأسنان',
                'زراعة الأسنان', 
                'جراحة الفم والوجه والفكين',
                'أسنان الأطفال',
                'علاج عصب الأسنان',
                'تجميل الأسنان',
            ],
            'popular_cities' => [
                'الرياض',
                'جدة',
                'الدمام',
                'مكة المكرمة',
                'المدينة المنورة',
                'الطائف',
                'تبوك',
                'الخبر',
            ],
        ];

        return response()->json($this->addGuestContext($suggestions, $request));
    }

    /**
     * Get content suitable for guest browsing homepage
     */
    public function guestHomepage(Request $request)
    {
        // Get featured doctors and clinics for guest users
        $featuredDoctors = $this->doctorRepository->getFeatured(6);
        $featuredClinics = $this->clinicRepository->getFeatured(4);
        $specializations = $this->specializationRepository->getPopular(8);

        return response()->json($this->addGuestContext([
            'featured_doctors' => DoctorResource::collection($featuredDoctors),
            'featured_clinics' => ClinicResource::collection($featuredClinics),
            'popular_specializations' => SpecializationResource::collection($specializations),
            'guest_benefits' => [
                'browse_doctors' => 'Browse thousands of qualified dentists',
                'read_reviews' => 'Read patient reviews and ratings',
                'view_specializations' => 'Explore different dental specializations',
                'search_locations' => 'Find dentists near your location',
            ],
            'call_to_action' => [
                'title' => 'Join thousands of satisfied patients',
                'description' => 'Create your account to book appointments, save favorites, and communicate with doctors',
                'primary_action' => 'Sign Up Now',
                'secondary_action' => 'Continue Browsing',
            ],
        ], $request));
    }
}