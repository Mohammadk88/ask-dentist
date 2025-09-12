<?php

namespace App\Http\Controllers;

use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\TreatmentPlan;
use App\Events\TreatmentPlanSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DoctorTreatmentPlanController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isDoctor()) {
                abort(403, 'Access denied. Doctor role required.');
            }
            return $next($request);
        });
    }

    /**
     * Show the dental plan builder for a treatment request
     */
    public function create(Request $request, TreatmentRequest $treatmentRequest)
    {
        // Check if doctor can create plan for this request
        $this->authorize('create', TreatmentPlan::class);
        
        // Check if doctor was dispatched for this request
        $doctor = Auth::user()->doctor;
        $dispatchRecord = $treatmentRequest->treatmentRequestDoctors()
            ->where('doctor_id', $doctor->id)
            ->first();
            
        if (!$dispatchRecord) {
            abort(403, 'You are not assigned to this treatment request.');
        }

        // Check if doctor already has a plan for this request
        $existingPlan = TreatmentPlan::where('treatment_request_id', $treatmentRequest->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if ($existingPlan && $existingPlan->status !== 'draft') {
            return redirect()->route('doctor.plans.show', $existingPlan)
                ->with('info', 'You already have a submitted plan for this request.');
        }

        return view('doctor.treatment-plans.create', [
            'treatmentRequest' => $treatmentRequest->load('patient.user'),
            'existingPlan' => $existingPlan,
        ]);
    }

    /**
     * Store a new treatment plan
     */
    public function store(Request $request)
    {
        $this->authorize('create', TreatmentPlan::class);

        $validated = $request->validate([
            'treatment_request_id' => 'required|exists:treatment_requests,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'diagnosis' => 'required|string',
            'services' => 'required|array|min:1',
            'total_cost' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'estimated_duration_days' => 'required|integer|min:1',
            'number_of_visits' => 'required|integer|min:1',
            'timeline' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $doctor = Auth::user()->doctor;
        $clinic = $doctor->doctorClinics()->with('clinic')->first()?->clinic;

        if (!$clinic) {
            return response()->json([
                'error' => 'Doctor must be associated with a clinic'
            ], 422);
        }

        // Verify doctor was dispatched for this request
        $treatmentRequest = TreatmentRequest::findOrFail($validated['treatment_request_id']);
        $dispatchRecord = $treatmentRequest->treatmentRequestDoctors()
            ->where('doctor_id', $doctor->id)
            ->first();
            
        if (!$dispatchRecord) {
            return response()->json([
                'error' => 'You are not assigned to this treatment request'
            ], 403);
        }

        $plan = TreatmentPlan::create([
            'treatment_request_id' => $validated['treatment_request_id'],
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'diagnosis' => $validated['diagnosis'],
            'services' => $validated['services'],
            'total_cost' => $validated['total_cost'],
            'currency' => $validated['currency'],
            'estimated_duration_days' => $validated['estimated_duration_days'],
            'number_of_visits' => $validated['number_of_visits'],
            'timeline' => $validated['timeline'] ?? [],
            'status' => 'draft',
            'expires_at' => now()->addDays(30),
            'notes' => $validated['notes'],
        ]);

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'message' => 'Treatment plan saved as draft'
        ]);
    }

    /**
     * Submit a treatment plan
     */
    public function submit(Request $request, TreatmentPlan $plan)
    {
        $this->authorize('update', $plan);

        if ($plan->status !== 'draft') {
            return response()->json([
                'error' => 'Only draft plans can be submitted'
            ], 422);
        }

        if (empty($plan->services)) {
            return response()->json([
                'error' => 'Plan must have at least one service item'
            ], 422);
        }

                $plan->update([
            'status' => 'submitted',
            'expires_at' => now()->addDays(30),
        ]);

        // Fire domain event for plan submission
        TreatmentPlanSubmitted::dispatch($plan);

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'message' => 'Treatment plan submitted successfully'
        ]);
    }

    /**
     * Show a specific treatment plan
     */
    public function show(TreatmentPlan $plan)
    {
        $this->authorize('view', $plan);

        return view('doctor.treatment-plans.show', [
            'plan' => $plan->load([
                'treatmentRequest.patient.user',
                'doctor.user',
                'clinic'
            ])
        ]);
    }

    /**
     * Update a draft treatment plan
     */
    public function update(Request $request, TreatmentPlan $plan)
    {
        $this->authorize('update', $plan);

        if ($plan->status !== 'draft') {
            return response()->json([
                'error' => 'Only draft plans can be updated'
            ], 422);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'diagnosis' => 'sometimes|required|string',
            'services' => 'sometimes|required|array|min:1',
            'total_cost' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|size:3',
            'estimated_duration_days' => 'sometimes|required|integer|min:1',
            'number_of_visits' => 'sometimes|required|integer|min:1',
            'timeline' => 'sometimes|nullable|array',
            'notes' => 'sometimes|nullable|string',
        ]);

        $plan->update($validated);

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'message' => 'Treatment plan updated successfully'
        ]);
    }

    /**
     * List doctor's treatment plans
     */
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $plans = TreatmentPlan::where('doctor_id', $doctor->id)
            ->with([
                'treatmentRequest.patient.user',
                'clinic'
            ])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        return view('doctor.treatment-plans.index', compact('plans'));
    }

    /**
     * Delete a draft treatment plan
     */
    public function destroy(TreatmentPlan $plan)
    {
        $this->authorize('delete', $plan);

        if ($plan->status !== 'draft') {
            return response()->json([
                'error' => 'Only draft plans can be deleted'
            ], 422);
        }

        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Treatment plan deleted successfully'
        ]);
    }
}
