<?php

namespace App\Listeners;

use App\Events\TreatmentPlanSubmitted;
use App\Infrastructure\Models\TreatmentPlan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CancelCompetingPlans implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TreatmentPlanSubmitted $event): void
    {
        $submittedPlan = $event->treatmentPlan;
        
        // Find all other draft plans for the same treatment request
        $competingPlans = TreatmentPlan::where('treatment_request_id', $submittedPlan->treatment_request_id)
            ->where('id', '!=', $submittedPlan->id)
            ->where('status', 'draft')
            ->get();

        foreach ($competingPlans as $plan) {
            $plan->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Automatically cancelled due to competing plan submission'
            ]);

            Log::info("Treatment plan {$plan->id} cancelled due to competing plan {$submittedPlan->id} submission");
        }

        // Optionally, you could also handle notification logic here
        // For example, notify other doctors that their draft plans were cancelled
        if ($competingPlans->count() > 0) {
            Log::info("Cancelled {$competingPlans->count()} competing draft plans for treatment request {$submittedPlan->treatment_request_id}");
            
            // TODO: Send notifications to affected doctors
            // NotificationService::notifyDoctorsOfCancelledPlans($competingPlans);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(TreatmentPlanSubmitted $event, \Throwable $exception): void
    {
        Log::error('Failed to process TreatmentPlanSubmitted event', [
            'treatment_plan_id' => $event->treatmentPlan->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
