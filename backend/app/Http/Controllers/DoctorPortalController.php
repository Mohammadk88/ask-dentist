<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorPortalController extends Controller
{
    /**
     * Display doctor dashboard
     */
    public function dashboard(): View
    {
        $doctor = auth()->user()->doctor;

        // Get doctor's active consultations
        $activeConsultations = $doctor->activeConsultations()->count();

        // Get pending treatment requests
        $pendingRequests = $doctor->consultations()
            ->where('treatment_requests.status', 'assigned')
            ->orderBy('treatment_requests.created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent completed cases
        $recentCases = $doctor->completedConsultations()
            ->orderBy('treatment_requests.updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('doctor.dashboard', compact(
            'doctor',
            'activeConsultations',
            'pendingRequests',
            'recentCases'
        ));
    }

    /**
     * Show dental plan builder for a specific request
     */
    public function planBuilder(string $id): View
    {
        $doctor = auth()->user()->doctor;

        // Find the consultation request
        $consultation = $doctor->consultations()
            ->where('treatment_requests.id', $id)
            ->firstOrFail();

        // Generate teeth chart IDs (t11 to t48)
        $teethIds = [];
        for ($i = 11; $i <= 18; $i++) {
            $teethIds[] = "t{$i}";
        }
        for ($i = 21; $i <= 28; $i++) {
            $teethIds[] = "t{$i}";
        }
        for ($i = 31; $i <= 38; $i++) {
            $teethIds[] = "t{$i}";
        }
        for ($i = 41; $i <= 48; $i++) {
            $teethIds[] = "t{$i}";
        }

        return view('doctor.plan-builder', compact(
            'consultation',
            'teethIds'
        ));
    }

    /**
     * Store new dental plan
     */
    public function storePlan(Request $request)
    {
        $request->validate([
            'consultation_id' => 'required|uuid|exists:consultations,id',
            'treatment_plan' => 'required|array',
            'notes' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'teeth_selection' => 'nullable|array',
        ]);

        $doctor = auth()->user()->doctor;

        // Verify the consultation belongs to this doctor
        $consultation = $doctor->consultations()
            ->where('treatment_requests.id', $request->consultation_id)
            ->firstOrFail();

        // Update consultation with treatment plan
        $consultation->update([
            'treatment_plan' => $request->treatment_plan,
            'doctor_notes' => $request->notes,
            'estimated_cost' => $request->estimated_cost,
            'teeth_selection' => $request->teeth_selection,
            'status' => 'plan_created',
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Treatment plan saved successfully',
            'consultation_id' => $consultation->id,
        ]);
    }

    /**
     * Submit dental plan to patient
     */
    public function submitPlan(Request $request, string $id)
    {
        $request->validate([
            'final_notes' => 'nullable|string',
            'send_notification' => 'boolean',
        ]);

        $doctor = auth()->user()->doctor;

        // Find the consultation
        $consultation = $doctor->consultations()
            ->where('treatment_requests.id', $id)
            ->where('treatment_requests.status', 'plan_created')
            ->firstOrFail();

        // Submit the plan
        $consultation->update([
            'status' => 'plan_submitted',
            'submitted_at' => now(),
            'final_notes' => $request->final_notes,
        ]);

        // Send notification if requested
        if ($request->boolean('send_notification')) {
            // TODO: Implement notification logic
            // $consultation->patient->notify(new TreatmentPlanReady($consultation));
        }

        return response()->json([
            'success' => true,
            'message' => 'Treatment plan submitted successfully',
            'consultation_id' => $consultation->id,
        ]);
    }
}
