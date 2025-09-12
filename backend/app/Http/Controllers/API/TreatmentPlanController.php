<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RequiresAuthentication;
use App\Models\TreatmentPlan;
use App\Domain\Events\PlanAccepted;
use App\Events\PlanAcceptedBroadcast;
use App\Http\Requests\RejectTreatmentPlanRequest;
use App\Services\NextStepsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TreatmentPlanController extends Controller
{
    use RequiresAuthentication;
    
    public function __construct(
        private NextStepsService $nextStepsService
    ) {}
    /**
     * Accept a treatment plan
     */
    public function accept(string $id): JsonResponse
    {
        $this->requireRole('Patient', 'accept_treatment_plan');

        try {
            $treatmentPlan = TreatmentPlan::findOrFail($id);

            // Validate that the plan can be accepted
            if ($treatmentPlan->status === 'accepted') {
                return response()->json([
                    'message' => 'Treatment plan is already accepted',
                    'status' => 'already_accepted'
                ], 400);
            }

            if ($treatmentPlan->status !== 'submitted') {
                return response()->json([
                    'message' => 'Only submitted treatment plans can be accepted',
                    'current_status' => $treatmentPlan->status
                ], 400);
            }

            if ($treatmentPlan->isExpired()) {
                return response()->json([
                    'message' => 'Treatment plan has expired',
                    'expired_at' => $treatmentPlan->expires_at
                ], 400);
            }

            // Check if there's already an accepted plan for this treatment request
            $existingAcceptedPlan = TreatmentPlan::where('treatment_request_id', $treatmentPlan->treatment_request_id)
                ->where('status', 'accepted')
                ->where('id', '!=', $treatmentPlan->id)
                ->first();

            if ($existingAcceptedPlan) {
                return response()->json([
                    'message' => 'Another treatment plan is already accepted for this request',
                    'accepted_plan_id' => $existingAcceptedPlan->id
                ], 400);
            }

            DB::transaction(function () use ($treatmentPlan) {
                // Accept the plan
                $treatmentPlan->accept();

                // Dispatch the domain event
                PlanAccepted::dispatch($treatmentPlan->id);

                // Broadcast the real-time event
                broadcast(new PlanAcceptedBroadcast($treatmentPlan))->toOthers();
            });

            // Load relationships for response
            $treatmentPlan->load(['doctor.user', 'clinic', 'treatmentRequest']);

            return response()->json([
                'message' => 'Treatment plan accepted successfully',
                'data' => [
                    'id' => $treatmentPlan->id,
                    'status' => $treatmentPlan->status,
                    'title' => $treatmentPlan->title,
                    'total_cost' => $treatmentPlan->total_cost,
                    'currency' => $treatmentPlan->currency,
                    'doctor' => [
                        'id' => $treatmentPlan->doctor->id,
                        'name' => $treatmentPlan->doctor->user->name,
                    ],
                    'clinic' => [
                        'id' => $treatmentPlan->clinic->id,
                        'name' => $treatmentPlan->clinic->name,
                    ],
                    'next_steps' => $this->nextStepsService->getNextSteps($treatmentPlan)
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error accepting treatment plan', [
                'treatment_plan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while accepting the treatment plan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Reject a treatment plan
     */
    public function reject(RejectTreatmentPlanRequest $request, string $id): JsonResponse
    {
        // Check if user is authenticated (this should be handled by middleware, but double-check)
        if (!auth()->check()) {
            return response()->json([
                'error' => 'auth_required',
                'message' => 'Login required.'
            ], 401);
        }

        try {
            $treatmentPlan = TreatmentPlan::findOrFail($id);

            // Validate that the plan can be rejected
            if ($treatmentPlan->status === 'rejected') {
                return response()->json([
                    'message' => 'Treatment plan is already rejected',
                    'status' => 'already_rejected'
                ], 400);
            }

            if ($treatmentPlan->status === 'accepted') {
                return response()->json([
                    'message' => 'Cannot reject an accepted treatment plan',
                    'status' => 'accepted'
                ], 400);
            }

            $reason = $request->validated()['reason'] ?? 'No reason provided';

            // Reject the plan
            $treatmentPlan->reject($reason);

            // Load relationships for response
            $treatmentPlan->load(['doctor.user', 'clinic']);

            return response()->json([
                'message' => 'Treatment plan rejected successfully',
                'data' => [
                    'id' => $treatmentPlan->id,
                    'status' => $treatmentPlan->status,
                    'title' => $treatmentPlan->title,
                    'rejection_reason' => $reason,
                    'doctor' => [
                        'id' => $treatmentPlan->doctor->id,
                        'name' => $treatmentPlan->doctor->user->name,
                    ],
                    'clinic' => [
                        'id' => $treatmentPlan->clinic->id,
                        'name' => $treatmentPlan->clinic->name,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error rejecting treatment plan', [
                'treatment_plan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while rejecting the treatment plan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
