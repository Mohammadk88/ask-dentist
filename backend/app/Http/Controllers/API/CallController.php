<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RequiresAuthentication;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CallController extends Controller
{
    use RequiresAuthentication;
    /**
     * Initiate a voice call with a doctor
     */
    public function initiate(Request $request): JsonResponse
    {
        $this->requireRole('Patient', 'initiate_voice_call');

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'call_type' => 'required|in:scheduled,instant',
            'scheduled_time' => 'required_if:call_type,scheduled|date|after:now',
        ]);

        // Here you would implement the actual call initiation logic
        // For now, returning a placeholder response
        
        return response()->json([
            'success' => true,
            'message' => 'Voice call request initiated successfully',
            'data' => [
                'call_id' => 'call_' . uniqid(),
                'doctor_id' => $request->doctor_id,
                'patient_id' => auth()->id(),
                'call_type' => $request->call_type,
                'scheduled_time' => $request->scheduled_time,
                'status' => 'pending',
                'created_at' => now()->toISOString(),
            ]
        ]);
    }
}