<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorTreatmentPlanController;
use App\Http\Controllers\Web\LiveChatController;
use App\Http\Controllers\Web\DoctorController;

Route::get('/', function () {
    return view('welcome');
});

// Basic login route for authentication redirects
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Logout route for web sessions
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/test-doctor', function () {
    return 'Doctor test route works';
});

// Guest redirects
Route::middleware('guest')->group(function () {
    // Doctor portal redirect for guests - redirect to admin login since doctors use Filament
    Route::get('/doctor', function () {
        return redirect('/admin/login');
    });
});

// Live Chat routes
Route::middleware(['auth:web', 'verified'])->prefix('chat')->name('chat.')->group(function () {
    // Chat dashboard
    Route::get('/', [LiveChatController::class, 'index'])->name('index');

    // Specific conversation
    Route::get('conversation/{consultationId}', [LiveChatController::class, 'show'])->name('conversation');

    // Send message
    Route::post('conversation/{consultationId}/message', [LiveChatController::class, 'sendMessage'])->name('send-message');

    // Get messages with pagination
    Route::get('conversation/{consultationId}/messages', [LiveChatController::class, 'getMessages'])->name('get-messages');

    // Mark messages as read
    Route::post('conversation/{consultationId}/read', [LiveChatController::class, 'markAsRead'])->name('mark-read');

    // Typing indicator
    Route::post('conversation/{consultationId}/typing', [LiveChatController::class, 'typing'])->name('typing');
});

// Doctor Web Portal routes
Route::middleware(['auth:web', 'verified', 'web.role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\DoctorPortalController::class, 'dashboard'])->name('dashboard');

    // Treatment Plan Builder for specific requests
    Route::get('requests/{id}/plan', [\App\Http\Controllers\DoctorPortalController::class, 'planBuilder'])
        ->name('requests.plan');

    // Treatment Plans API endpoints
    Route::post('plans', [\App\Http\Controllers\DoctorPortalController::class, 'storePlan'])
        ->name('plans.store');

    Route::post('plans/{id}/submit', [\App\Http\Controllers\DoctorPortalController::class, 'submitPlan'])
        ->name('plans.submit');

    // Legacy routes for existing functionality
    Route::get('/treatment-plan-builder', [DoctorController::class, 'treatmentPlanBuilder'])->name('treatment-plan-builder');

    Route::get('requests/{treatmentRequest}/plan-legacy', [DoctorTreatmentPlanController::class, 'create'])
        ->name('requests.plan.create');

    Route::prefix('plans-legacy')->name('plans.')->group(function () {
        Route::get('/', [DoctorTreatmentPlanController::class, 'index'])->name('index');
        Route::get('{plan}', [DoctorTreatmentPlanController::class, 'show'])->name('show');
        Route::post('/', [DoctorTreatmentPlanController::class, 'store'])->name('store');
        Route::put('{plan}', [DoctorTreatmentPlanController::class, 'update'])->name('update');
        Route::delete('{plan}', [DoctorTreatmentPlanController::class, 'destroy'])->name('destroy');
        Route::post('{plan}/submit', [DoctorTreatmentPlanController::class, 'submit'])->name('submit');
    });
});
