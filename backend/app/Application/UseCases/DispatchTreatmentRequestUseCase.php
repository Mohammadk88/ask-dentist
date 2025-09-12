<?php

namespace App\Application\UseCases;

use App\Application\DTOs\DispatchTreatmentRequestDTO;
use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\TreatmentRequestDoctor;
use App\Jobs\NotifyDoctorOfTreatmentRequestJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DispatchTreatmentRequestUseCase
{
    public function execute(DispatchTreatmentRequestDTO $dto): array
    {
        Log::info('Starting treatment request dispatch', [
            'treatment_request_id' => $dto->treatmentRequestId,
            'max_doctors' => $dto->maxDoctors
        ]);

        return DB::transaction(function () use ($dto) {
            // 1. Find the treatment request
            $treatmentRequest = TreatmentRequest::findOrFail($dto->treatmentRequestId);
            
            // 2. Validate treatment request can be dispatched
            $this->validateTreatmentRequestForDispatch($treatmentRequest);
            
            // 3. Filter and score doctors
            $eligibleDoctors = $this->getEligibleDoctors($treatmentRequest);
            
            if ($eligibleDoctors->isEmpty()) {
                throw new \DomainException('No eligible doctors found for this treatment request');
            }
            
            // 4. Score and rank doctors
            $scoredDoctors = $this->scoreAndRankDoctors($eligibleDoctors);
            
            // 5. Select top doctors (up to maxDoctors)
            $selectedDoctors = $scoredDoctors->take($dto->maxDoctors);
            
            if ($selectedDoctors->count() < $dto->maxDoctors) {
                Log::warning('Fewer doctors available than requested', [
                    'requested' => $dto->maxDoctors,
                    'available' => $selectedDoctors->count(),
                    'treatment_request_id' => $dto->treatmentRequestId
                ]);
            }
            
            // 6. Create treatment_request_doctors records
            $dispatches = $this->createDispatchRecords($treatmentRequest, $selectedDoctors);
            
            // 7. Queue notifications
            $this->queueNotifications($dispatches);
            
            // 8. Update treatment request status
            $treatmentRequest->update(['status' => 'reviewing']);
            
            Log::info('Treatment request dispatch completed', [
                'treatment_request_id' => $dto->treatmentRequestId,
                'doctors_dispatched' => $dispatches->count(),
                'doctor_ids' => $dispatches->pluck('doctor_id')->toArray()
            ]);
            
            return [
                'treatment_request_id' => $treatmentRequest->id,
                'dispatched_doctors' => $dispatches->toArray(),
                'total_dispatched' => $dispatches->count(),
            ];
        });
    }

    private function validateTreatmentRequestForDispatch(TreatmentRequest $treatmentRequest): void
    {
        if (!in_array($treatmentRequest->status, ['pending', 'reviewing'])) {
            throw new \DomainException(
                "Treatment request cannot be dispatched. Current status: {$treatmentRequest->status}"
            );
        }

        // Check if already dispatched
        if ($treatmentRequest->treatmentRequestDoctors()->exists()) {
            throw new \DomainException('Treatment request has already been dispatched');
        }
    }

    private function getEligibleDoctors(TreatmentRequest $treatmentRequest): \Illuminate\Database\Eloquent\Collection
    {
        return Doctor::availableForDispatch()
            ->with(['user', 'treatmentPlans' => function ($query) {
                $query->whereIn('status', ['active', 'in_progress', 'scheduled']);
            }])
            ->get()
            ->filter(function (Doctor $doctor) use ($treatmentRequest) {
                // Additional business logic filters can be added here
                // For example: specialty matching, location, availability, etc.
                
                // Skip if doctor is in patient's clinic and has conflict of interest
                if ($this->hasConflictOfInterest($doctor, $treatmentRequest)) {
                    return false;
                }
                
                return true;
            });
    }

    private function hasConflictOfInterest(Doctor $doctor, TreatmentRequest $treatmentRequest): bool
    {
        // Business rule: Doctor cannot treat patients from their own clinic
        // This can be customized based on business requirements
        return false; // For now, no conflict of interest logic
    }

    private function scoreAndRankDoctors(\Illuminate\Database\Eloquent\Collection $doctors): \Illuminate\Database\Eloquent\Collection
    {
        return $doctors->map(function (Doctor $doctor) {
            $doctor->dispatch_score = $doctor->calculateDispatchScore();
            return $doctor;
        })->sortBy('dispatch_score')->values(); // Sort by the calculated score (lower is better)
    }

    private function createDispatchRecords(
        TreatmentRequest $treatmentRequest, 
        \Illuminate\Database\Eloquent\Collection $selectedDoctors
    ): \Illuminate\Database\Eloquent\Collection {
        $dispatches = new \Illuminate\Database\Eloquent\Collection();
        
        foreach ($selectedDoctors as $index => $doctor) {
            $dispatch = TreatmentRequestDoctor::create([
                'treatment_request_id' => $treatmentRequest->id,
                'doctor_id' => $doctor->id,
                'dispatch_order' => $index + 1,
                'dispatch_score' => $doctor->dispatch_score,
                'status' => 'pending',
            ]);
            
            $dispatches->push($dispatch);
        }
        
        return $dispatches;
    }

    private function queueNotifications(\Illuminate\Database\Eloquent\Collection $dispatches): void
    {
        foreach ($dispatches as $dispatch) {
            // Queue email/push notifications
            NotifyDoctorOfTreatmentRequestJob::dispatch($dispatch)
                ->delay(now()->addSeconds($dispatch->dispatch_order * 5)); // Stagger notifications
            
            // Mark as notified
            $dispatch->markAsNotified();
        }
    }
}