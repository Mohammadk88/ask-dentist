<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\HexagonalAuthController;
use App\Http\Controllers\API\HexagonalConsultationController;
use App\Http\Controllers\API\TreatmentRequestController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\VerificationController;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\MediaController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\API\FavoritesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health endpoint
Route::get('health', function () {
    $startTime = microtime(true);

    try {
        $health = [
            'ok' => true,
            'app' => 'ask.dentist',
            'env' => app()->environment(),
            'timestamp' => now()->toISOString(),
            'remote_addr' => request()->ip(),
            'uri' => request()->getRequestUri()
        ];

        // Log request for debugging
        \Log::info('Health check', [
            'uri' => request()->getRequestUri(),
            'remote_addr' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        $endTime = microtime(true);
        $health['response_time_ms'] = round(($endTime - $startTime) * 1000, 2);

        return response()->json($health);
    } catch (\Exception $e) {
        $endTime = microtime(true);

        return response()->json([
            'ok' => false,
            'error' => $e->getMessage(),
            'response_time_ms' => round(($endTime - $startTime) * 1000, 2)
        ], 500);
    }
});

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'registerPatient'])
        ->middleware(['throttle:5,1', 'audit']); // 5 attempts per minute + audit
    Route::post('register/patient', [AuthController::class, 'registerPatient'])
        ->middleware(['throttle:5,1', 'audit']); // 5 attempts per minute + audit
    Route::post('register/doctor', [AuthController::class, 'registerDoctor'])
        ->middleware(['throttle:5,1', 'audit']); // 5 attempts per minute + audit
    Route::post('login', [AuthController::class, 'login'])
        ->middleware(['throttle:10,1', 'audit']); // 10 attempts per minute + audit

    // Password Reset Routes
    Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword'])
        ->middleware(['throttle:3,1', 'audit']); // 3 attempts per minute + audit
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware(['throttle:5,1', 'audit']); // 5 attempts per minute + audit

    // Email Verification Routes (public)
    Route::post('verify-email', [VerificationController::class, 'verifyEmail'])
        ->middleware('throttle:10,1'); // 10 attempts per minute
});

// Hexagonal Architecture authentication routes (clean architecture implementation)
Route::prefix('v2/auth')->group(function () {
    Route::post('register/patient', [HexagonalAuthController::class, 'registerPatient'])
        ->middleware('audit');
    Route::post('register/doctor', [HexagonalAuthController::class, 'registerDoctor'])
        ->middleware('audit');
    Route::post('login', [HexagonalAuthController::class, 'login'])
        ->middleware('audit');
});

// Hexagonal Architecture consultation routes (clean architecture implementation)
Route::middleware(['auth:api', 'throttle:30,1'])->prefix('v2/consultations')->group(function () {
    Route::post('/', [HexagonalConsultationController::class, 'create'])
        ->middleware('audit');
    Route::get('/{id}', [HexagonalConsultationController::class, 'show']);
    Route::get('/doctor/{doctorId}', [HexagonalConsultationController::class, 'getDoctorConsultations']);
    Route::get('/patient/{patientId}', [HexagonalConsultationController::class, 'getPatientConsultations']);
    Route::post('/{id}/messages', [HexagonalConsultationController::class, 'sendMessage'])
        ->middleware(['throttle:60,1', 'audit']); // 60 messages per minute + audit
});

// Protected authentication routes
Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('audit');
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('refresh', [AuthController::class, 'refresh'])
        ->middleware('throttle:10,1'); // 10 refresh attempts per minute
    Route::post('token/refresh', [AuthController::class, 'refresh'])
        ->middleware('throttle:10,1'); // 10 refresh attempts per minute (alias)
    Route::post('change-password', [PasswordResetController::class, 'changePassword'])
        ->middleware(['throttle:5,1', 'audit']); // 5 password changes per minute + audit

    // Token management routes
    Route::get('token-info', [AuthController::class, 'tokenInfo']);
    Route::get('tokens', [AuthController::class, 'tokens']);
    Route::post('revoke-token', [AuthController::class, 'revokeToken'])
        ->middleware(['throttle:10,1', 'audit']); // 10 token revocation attempts per minute + audit

    // Email & Phone Verification Routes (authenticated)
    Route::post('send-email-verification', [VerificationController::class, 'sendEmailVerification'])
        ->middleware('throttle:3,1'); // 3 verification emails per minute
    Route::post('send-phone-verification', [VerificationController::class, 'sendPhoneVerification'])
        ->middleware('throttle:3,1'); // 3 SMS per minute
    Route::post('verify-phone', [VerificationController::class, 'verifyPhone'])
        ->middleware(['throttle:10,1', 'audit']); // 10 phone verification attempts per minute + audit
    Route::get('verification-status', [VerificationController::class, 'getVerificationStatus']);
});

// Enhanced /me endpoint (alternative to /auth/profile)
Route::middleware('auth:api')->get('me', [AuthController::class, 'profile']);

// File management routes with rate limiting and audit logging
Route::middleware('auth:api')->prefix('files')->group(function () {
    Route::post('upload', [FileController::class, 'upload'])
        ->middleware(['throttle:20,1', 'audit']); // 20 uploads per minute + audit
    Route::get('/', [FileController::class, 'list'])
        ->middleware('throttle:60,1'); // 60 list requests per minute
    Route::get('{id}/download', [FileController::class, 'download'])
        ->middleware(['throttle:60,1', 'audit']) // 60 downloads per minute + audit
        ->name('api.files.download');
    Route::get('{id}/signed-url', [FileController::class, 'getSignedUrl'])
        ->middleware('throttle:30,1'); // 30 signed URL requests per minute
});

// Profile management routes
Route::middleware(['auth:api', 'throttle:30,1'])->prefix('profile')->group(function () {
    Route::put('/', [ProfileController::class, 'updateProfile'])
        ->middleware('audit');
    Route::put('/patient', [ProfileController::class, 'updatePatientProfile'])
        ->middleware(['custom.role:Patient', 'audit']);
    Route::put('/doctor', [ProfileController::class, 'updateDoctorProfile'])
        ->middleware(['custom.role:Doctor', 'audit']);
    Route::post('/picture', [ProfileController::class, 'uploadProfilePicture'])
        ->middleware('throttle:10,1'); // 10 picture uploads per minute
    Route::delete('/picture', [ProfileController::class, 'deleteProfilePicture'])
        ->middleware('audit');
    Route::put('/consent', [ProfileController::class, 'updateConsent'])
        ->middleware(['custom.role:Patient', 'audit']);
});

// Treatment request management routes
Route::middleware(['auth:api', 'throttle:60,1'])->prefix('treatment-requests')->group(function () {
    Route::post('{id}/dispatch', [TreatmentRequestController::class, 'dispatch'])
        ->middleware('audit'); // Dispatch treatment request to doctors
    Route::post('{treatmentRequest}/accept/{doctor}', [TreatmentRequestController::class, 'accept'])
        ->name('api.treatment-requests.accept');
    Route::post('{treatmentRequest}/decline/{doctor}', [TreatmentRequestController::class, 'decline'])
        ->name('api.treatment-requests.decline');
});

// Review management routes - Support guest browsing of reviews
Route::middleware(['opt.auth', 'throttle:60,1'])->prefix('reviews')->group(function () {
    // Public endpoints for browsing reviews
    Route::get('questions', [App\Http\Controllers\API\ReviewController::class, 'getQuestions']);
    Route::get('doctors/{doctorId}', [App\Http\Controllers\API\ReviewController::class, 'getDoctorReviews']);

    // Authenticated endpoints for submitting reviews
    Route::middleware('auth:api')->group(function () {
        Route::post('doctors/{doctorId}', [App\Http\Controllers\API\ReviewController::class, 'submitReview'])
            ->middleware(['custom.role:Patient', 'audit']); // Only patients can submit reviews
    });
});

// FCM (Push notifications) management routes
Route::middleware(['auth:api', 'throttle:30,1'])->prefix('fcm')->group(function () {
    Route::post('tokens', [App\Http\Controllers\API\FCMController::class, 'addToken'])
        ->middleware('audit'); // Add FCM token
    Route::delete('tokens', [App\Http\Controllers\API\FCMController::class, 'removeToken'])
        ->middleware('audit'); // Remove FCM token
    Route::get('tokens', [App\Http\Controllers\API\FCMController::class, 'getTokens']); // Get user tokens
    Route::post('subscribe', [App\Http\Controllers\API\FCMController::class, 'subscribeToTopic'])
        ->middleware('audit'); // Subscribe to topic
    Route::post('unsubscribe', [App\Http\Controllers\API\FCMController::class, 'unsubscribeFromTopic'])
        ->middleware('audit'); // Unsubscribe from topic
});

// Locale/Internationalization routes
Route::middleware(['throttle:60,1'])->prefix('locale')->group(function () {
    Route::get('available', [App\Http\Controllers\API\LocaleController::class, 'getAvailableLocales']);
    Route::get('current', [App\Http\Controllers\API\LocaleController::class, 'getCurrentLocale']);
    Route::get('translations', [App\Http\Controllers\API\LocaleController::class, 'getTranslations']);

    Route::middleware('auth:api')->group(function () {
        Route::post('set', [App\Http\Controllers\API\LocaleController::class, 'setUserLocale'])
            ->middleware('audit'); // Set user locale preference
    });
});

// ===================================================================
// PUBLIC ROUTES (Guest Mode with Optional Authentication)
// ===================================================================

// Main public endpoints with optional authentication
Route::middleware(['opt.auth', 'throttle:60,1'])->group(function () {
    // Home feed endpoints
    Route::get('home', [HomeController::class, 'index'])
        ->name('api.home.index');
    Route::get('stories', [HomeController::class, 'getStories'])
        ->name('api.home.stories');
    Route::get('clinics/top', [HomeController::class, 'getClinics'])
        ->name('api.home.clinics');
    Route::get('doctors/top', [HomeController::class, 'getDoctors'])
        ->name('api.home.doctors');
    Route::get('before-after', [HomeController::class, 'getBeforeAfter'])
        ->name('api.home.before-after');

    // Individual resource endpoints
    Route::get('doctors/{id}', [App\Http\Controllers\Api\BrowsingController::class, 'doctor'])
        ->name('api.doctor.show');
    Route::get('clinics/{id}', [App\Http\Controllers\Api\BrowsingController::class, 'clinic'])
        ->name('api.clinic.show');

    // Search functionality
    Route::get('search', [App\Http\Controllers\Api\BrowsingController::class, 'search'])
        ->name('api.search');
});

// Public reference data (no auth required)
Route::get('specializations', [ProfileController::class, 'getSpecializations']);

// ===================================================================
// PROTECTED ROUTES (Authentication Required)
// ===================================================================

// Favorites management (authentication required)
Route::middleware(['auth:api', 'throttle:60,1'])->prefix('favorites')->group(function () {
    Route::post('doctors/{id}', [FavoritesController::class, 'toggleDoctorFavorite'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.favorites.toggle-doctor');
    Route::get('doctors', [FavoritesController::class, 'getFavoriteDoctors'])
        ->middleware('custom.role:Patient')
        ->name('api.favorites.doctors');
});

// Appointments management (authentication required)
Route::middleware(['auth:api', 'throttle:30,1'])->prefix('appointments')->group(function () {
    Route::post('/', [App\Http\Controllers\Api\AppointmentController::class, 'book'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.appointments.book');
});

// Communication endpoints (authentication required)
Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
    // Messages
    Route::post('messages', [App\Http\Controllers\Api\MessageController::class, 'start'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.messages.start');

    // Calls (placeholder)
    Route::post('calls', [App\Http\Controllers\Api\CallController::class, 'initiate'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.calls.initiate');

    // Videos (placeholder)
    Route::post('videos', [App\Http\Controllers\Api\VideoController::class, 'initiate'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.videos.initiate');
});

// Treatment requests (authentication required)
Route::middleware(['auth:api', 'throttle:30,1'])->prefix('treatment-requests')->group(function () {
    Route::post('/', [TreatmentRequestController::class, 'store'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.treatment-requests.store');
});

// Treatment plans (authentication required)
Route::middleware(['auth:api', 'throttle:30,1'])->prefix('plans')->group(function () {
    Route::post('{id}/accept', [App\Http\Controllers\Api\TreatmentPlanController::class, 'accept'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.plans.accept');
    Route::post('{id}/reject', [App\Http\Controllers\Api\TreatmentPlanController::class, 'reject'])
        ->middleware(['custom.role:Patient', 'audit'])
        ->name('api.plans.reject');
});

// Media access routes for secure file serving
Route::middleware(['throttle:300,1'])->prefix('media')->group(function () {
    // Signed media routes for private/sensitive content (before/after photos, etc.)
    Route::get('{id}', [App\Http\Controllers\API\MediaController::class, 'show'])
        ->name('api.media.signed');

    // Public media routes for non-sensitive content
    Route::get('public/{type}/{filename}', [App\Http\Controllers\API\MediaController::class, 'showPublic'])
        ->name('api.media.public');
});

// Health check endpoint
Route::get('health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Version 1 API routes
Route::prefix('v1')->group(function () {
    // Health check endpoint
    Route::get('health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
            'version' => '1.0.0'
        ]);
    });

    // Home endpoint
    Route::middleware(['opt.auth', 'throttle:60,1'])->get('home', [HomeController::class, 'index'])
        ->name('api.v1.home.index');
});

// API documentation route (handled by Scribe)
// Available at /api/docs when Scribe is configured
