<?php

namespace App\Jobs;

use App\Infrastructure\Models\TreatmentRequestDoctor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotifyDoctorOfTreatmentRequestJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public TreatmentRequestDoctor $treatmentRequestDoctor
    ) {
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dispatch = $this->treatmentRequestDoctor;
        $doctor = $dispatch->doctor()->with('user')->first();
        $treatmentRequest = $dispatch->treatmentRequest()->with('patient.user')->first();

        if (!$doctor || !$treatmentRequest) {
            Log::error('Missing doctor or treatment request for notification', [
                'dispatch_id' => $dispatch->id,
                'doctor_id' => $dispatch->doctor_id,
                'treatment_request_id' => $dispatch->treatment_request_id,
            ]);
            return;
        }

        try {
            // Send email notification
            $this->sendEmailNotification($doctor, $treatmentRequest, $dispatch);
            
            // Send push notification (placeholder)
            $this->sendPushNotification($doctor, $treatmentRequest, $dispatch);
            
            Log::info('Doctor notification sent successfully', [
                'doctor_id' => $doctor->id,
                'treatment_request_id' => $treatmentRequest->id,
                'dispatch_order' => $dispatch->dispatch_order,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send doctor notification', [
                'doctor_id' => $doctor->id,
                'treatment_request_id' => $treatmentRequest->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e; // Re-throw to trigger job retry
        }
    }

    private function sendEmailNotification($doctor, $treatmentRequest, $dispatch): void
    {
        // Placeholder for email notification
        // In a real implementation, this would use Laravel Mail with a proper Mailable class
        
        $emailData = [
            'doctor_name' => $doctor->user->name,
            'patient_name' => $treatmentRequest->patient->user->name,
            'treatment_title' => $treatmentRequest->title,
            'urgency' => $treatmentRequest->urgency,
            'dispatch_order' => $dispatch->dispatch_order,
            'accept_url' => route('api.treatment-requests.accept', [
                'treatmentRequest' => $treatmentRequest->id,
                'doctor' => $doctor->id,
            ]),
            'decline_url' => route('api.treatment-requests.decline', [
                'treatmentRequest' => $treatmentRequest->id,
                'doctor' => $doctor->id,
            ]),
        ];

        // TODO: Implement actual email sending
        Log::info('Email notification would be sent', [
            'to' => $doctor->user->email,
            'data' => $emailData,
        ]);
    }

    private function sendPushNotification($doctor, $treatmentRequest, $dispatch): void
    {
        // Placeholder for push notification
        // In a real implementation, this would integrate with FCM, APNS, or similar service
        
        $pushData = [
            'title' => 'New Treatment Request',
            'body' => "New {$treatmentRequest->urgency} priority case: {$treatmentRequest->title}",
            'data' => [
                'treatment_request_id' => $treatmentRequest->id,
                'dispatch_order' => $dispatch->dispatch_order,
                'type' => 'treatment_request_dispatch',
            ],
        ];

        // TODO: Implement actual push notification
        Log::info('Push notification would be sent', [
            'user_id' => $doctor->user->id,
            'data' => $pushData,
        ]);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Doctor notification job failed permanently', [
            'dispatch_id' => $this->treatmentRequestDoctor->id,
            'doctor_id' => $this->treatmentRequestDoctor->doctor_id,
            'treatment_request_id' => $this->treatmentRequestDoctor->treatment_request_id,
            'error' => $exception->getMessage(),
        ]);

        // Could implement fallback notification method here
        // Or mark the dispatch as failed for manual follow-up
    }
}
