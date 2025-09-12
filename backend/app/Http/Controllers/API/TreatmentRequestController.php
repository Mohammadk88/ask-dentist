<?php

namespace App\Http\Controllers\API;

use App\Application\DTOs\DispatchTreatmentRequestDTO;
use App\Application\UseCases\DispatchTreatmentRequestUseCase;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RequiresAuthentication;
use App\Infrastructure\Models\TreatmentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @group Treatment Requests
 *
 * Treatment request management and dispatch system
 */
class TreatmentRequestController extends Controller
{
    use RequiresAuthentication;
    
    public function __construct(
        private DispatchTreatmentRequestUseCase $dispatchUseCase
    ) {}

    /**
     * Submit a new treatment request
     *
     * @authenticated
     */
    public function store(Request $request): JsonResponse
    {
        $this->requireRole('Patient', 'submit_treatment_request');

        $request->validate([
            'specialization_id' => 'required|exists:specializations,id',
            'description' => 'required|string|max:2000',
            'urgency' => 'required|in:low,medium,high,urgent',
            'preferred_location' => 'nullable|string|max:200',
            'budget_range' => 'nullable|string|max:100',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        // Here you would implement the actual treatment request creation logic
        // For now, returning a placeholder response
        
        return response()->json([
            'success' => true,
            'message' => 'Treatment request submitted successfully',
            'data' => [
                'treatment_request_id' => 'tr_' . uniqid(),
                'patient_id' => auth()->id(),
                'specialization_id' => $request->specialization_id,
                'description' => $request->description,
                'urgency' => $request->urgency,
                'preferred_location' => $request->preferred_location,
                'budget_range' => $request->budget_range,
                'status' => 'pending',
                'created_at' => now()->toISOString(),
            ]
        ], 201);
    }

    /**
     * Dispatch treatment request to doctors
     *
     * Dispatches a treatment request to up to 5 eligible doctors based on a scoring algorithm.
     * The scoring considers: active patients count (asc), rating (desc), response time (asc), 
     * and rotation fairness (asc).
     *
     * @authenticated
     *
     * @urlParam id integer required The treatment request ID. Example: 1
     * @bodyParam max_doctors integer optional Maximum number of doctors to dispatch to (default: 5). Example: 5
     *
     * @response 200 {
     *   "message": "Treatment request dispatched successfully",
     *   "data": {
     *     "treatment_request_id": 1,
     *     "dispatched_doctors": [
     *       {
     *         "id": 1,
     *         "treatment_request_id": 1,
     *         "doctor_id": 5,
     *         "dispatch_order": 1,
     *         "dispatch_score": 1205.2500,
     *         "status": "pending",
     *         "notified_at": "2025-09-05T10:00:00.000000Z"
     *       }
     *     ],
     *     "total_dispatched": 5
     *   }
     * }
     *
     * @response 403 {
     *   "message": "You are not authorized to dispatch this treatment request"
     * }
     *
     * @response 422 {
     *   "message": "Treatment request cannot be dispatched",
     *   "error": "Treatment request has already been dispatched"
     * }
     */
    public function dispatch(Request $request, int $id): JsonResponse
    {
        try {
            // Find treatment request
            $treatmentRequest = TreatmentRequest::findOrFail($id);
            
            // Authorization check
            $this->authorize('dispatch', $treatmentRequest);
            
            // Validate request
            $request->validate([
                'max_doctors' => 'nullable|integer|min:1|max:10'
            ]);
            
            // Create DTO
            $dto = new DispatchTreatmentRequestDTO(
                treatmentRequestId: $id,
                maxDoctors: $request->input('max_doctors', 5)
            );
            
            // Execute use case
            $result = $this->dispatchUseCase->execute($dto);
            
            return response()->json([
                'message' => 'Treatment request dispatched successfully',
                'data' => $result
            ], 200);
            
        } catch (\DomainException $e) {
            return response()->json([
                'message' => 'Treatment request cannot be dispatched',
                'error' => $e->getMessage()
            ], 422);
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not authorized to dispatch this treatment request'
            ], 403);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while dispatching the treatment request',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Doctor accepts treatment request
     *
     * @authenticated
     */
    public function accept(Request $request, int $treatmentRequestId, int $doctorId): JsonResponse
    {
        // TODO: Implement doctor acceptance logic
        return response()->json(['message' => 'Feature not yet implemented'], 501);
    }

    /**
     * Doctor declines treatment request
     *
     * @authenticated
     */
    public function decline(Request $request, int $treatmentRequestId, int $doctorId): JsonResponse
    {
        // TODO: Implement doctor decline logic
        return response()->json(['message' => 'Feature not yet implemented'], 501);
    }
}
