<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RequiresAuthentication;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    use RequiresAuthentication;
    /**
     * Book a consultation appointment
     */
    public function book(Request $request): JsonResponse
    {
        $this->requireRole('Patient', 'book_appointment');

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'consultation_type' => 'required|in:online,in_person',
            'preferred_date' => 'required|date|after:now',
            'preferred_time' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // Here you would implement the actual appointment booking logic
        // For now, returning a placeholder response
        
        return response()->json([
            'success' => true,
            'message' => 'Appointment booking request submitted successfully',
            'data' => [
                'appointment_id' => 'temp_' . uniqid(),
                'doctor_id' => $request->doctor_id,
                'consultation_type' => $request->consultation_type,
                'preferred_date' => $request->preferred_date,
                'preferred_time' => $request->preferred_time,
                'status' => 'pending',
                'notes' => $request->notes,
            ]
        ]);
    }
}