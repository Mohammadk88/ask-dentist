<?php

namespace App\Domain\Handlers;

use App\Domain\Events\PlanAccepted;
use App\Models\TreatmentPlan;
use App\Models\Doctor;
use App\Models\User;
use App\Notifications\TreatmentPlanCancelled;
use App\Notifications\TreatmentPlanAccepted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PlanAcceptedHandler
{
    public function handle(PlanAccepted $event): void
    {
        try {
            $acceptedPlan = TreatmentPlan::find($event->treatmentPlanId);
            
            if (!$acceptedPlan) {
                Log::error("Treatment plan not found: {$event->treatmentPlanId}");
                return;
            }

            // Mark all other plans for the same treatment request as cancelled
            $cancelledPlans = TreatmentPlan::where('treatment_request_id', $acceptedPlan->treatment_request_id)
                ->where('id', '!=', $acceptedPlan->id)
                ->whereIn('status', ['draft', 'submitted'])
                ->get();

            foreach ($cancelledPlans as $plan) {
                $plan->cancel('Plan cancelled due to another plan being accepted for the same treatment request');
                
                // Notify the doctor whose plan was cancelled
                $this->notifyDoctorPlanCancelled($plan);
            }

            // Notify the doctor whose plan was accepted
            $this->notifyDoctorPlanAccepted($acceptedPlan);

            // Notify other doctors involved in the treatment request
            $this->notifyOtherDoctorsInRequest($acceptedPlan, $cancelledPlans);

            Log::info("Treatment plan {$acceptedPlan->id} accepted, {$cancelledPlans->count()} other plans cancelled");

        } catch (\Exception $e) {
            Log::error("Error handling PlanAccepted event: " . $e->getMessage(), [
                'treatment_plan_id' => $event->treatmentPlanId,
                'exception' => $e
            ]);
        }
    }

    private function notifyDoctorPlanAccepted(TreatmentPlan $acceptedPlan): void
    {
        $doctor = $acceptedPlan->doctor;
        if ($doctor && $doctor->user) {
            $doctor->user->notify(new TreatmentPlanAccepted($acceptedPlan));
        }
    }

    private function notifyDoctorPlanCancelled(TreatmentPlan $cancelledPlan): void
    {
        $doctor = $cancelledPlan->doctor;
        if ($doctor && $doctor->user) {
            $doctor->user->notify(new TreatmentPlanCancelled($cancelledPlan));
        }
    }

    private function notifyOtherDoctorsInRequest(TreatmentPlan $acceptedPlan, $cancelledPlans): void
    {
        // Get all doctors who had plans for this treatment request
        $doctorIds = $cancelledPlans->pluck('doctor_id')->unique();
        
        $doctors = Doctor::whereIn('id', $doctorIds)
            ->with('user')
            ->get();

        foreach ($doctors as $doctor) {
            if ($doctor->user) {
                $doctor->user->notify(new TreatmentPlanCancelled(
                    $cancelledPlans->where('doctor_id', $doctor->id)->first(),
                    'Another treatment plan was accepted for this patient'
                ));
            }
        }
    }
}