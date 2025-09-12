<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RequiresAuthentication;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    use RequiresAuthentication;
    /**
     * Start a chat conversation with a doctor
     */
    public function start(Request $request): JsonResponse
    {
        $this->requireRole('Patient', 'start_chat');

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'message' => 'required|string|max:1000',
            'subject' => 'nullable|string|max:200',
        ]);

        // Here you would implement the actual message sending logic
        // For now, returning a placeholder response
        
        return response()->json([
            'success' => true,
            'message' => 'Chat conversation started successfully',
            'data' => [
                'conversation_id' => 'conv_' . uniqid(),
                'doctor_id' => $request->doctor_id,
                'patient_id' => auth()->id(),
                'initial_message' => $request->message,
                'subject' => $request->subject,
                'status' => 'active',
                'created_at' => now()->toISOString(),
            ]
        ]);
    }
}