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
    Route::get('/', [DoctorController::class, 'dashboard'])->name('dashboard');

    // Treatment Plan Builder
    Route::get('/treatment-plan-builder', [DoctorController::class, 'treatmentPlanBuilder'])->name('treatment-plan-builder');

    // Show dental plan builder for a treatment request
    Route::get('requests/{treatmentRequest}/plan', [DoctorTreatmentPlanController::class, 'create'])
        ->name('requests.plan.create');

    // Treatment Plans management
    Route::prefix('plans')->name('plans.')->group(function () {
        // List all plans for the doctor
        Route::get('/', [DoctorTreatmentPlanController::class, 'index'])
            ->name('index');

        // Show specific plan
        Route::get('{plan}', [DoctorTreatmentPlanController::class, 'show'])
            ->name('show');

        // AJAX endpoints for the Livewire component
        Route::post('/', [DoctorTreatmentPlanController::class, 'store'])
            ->name('store');

        Route::put('{plan}', [DoctorTreatmentPlanController::class, 'update'])
            ->name('update');

        Route::delete('{plan}', [DoctorTreatmentPlanController::class, 'destroy'])
            ->name('destroy');

        // Submit plan (changes status from draft to submitted)
        Route::post('{plan}/submit', [DoctorTreatmentPlanController::class, 'submit'])
            ->name('submit');
    });
});
