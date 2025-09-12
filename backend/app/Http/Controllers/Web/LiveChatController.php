<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LiveChatController extends Controller
{
    /**
     * Display the live chat interface
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's consultations based on role
        $consultations = $this->getUserConsultations($user);
        
        return view('chat.index', compact('consultations', 'user'));
    }

    /**
     * Show specific conversation
     */
    public function show(Request $request, $consultationId)
    {
        $user = Auth::user();
        $consultation = $this->getAuthorizedConsultation($user, $consultationId);
        
        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found or unauthorized'], 404);
        }

        // Load messages with pagination
        $messages = $consultation->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Mark messages as read
        $this->markMessagesAsRead($consultation, $user);

        if ($request->expectsJson()) {
            return response()->json([
                'consultation' => $consultation->load(['patient.user', 'doctor.user']),
                'messages' => $messages,
            ]);
        }

        return view('chat.conversation', compact('consultation', 'messages', 'user'));
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request, $consultationId): JsonResponse
    {
        $user = Auth::user();
        $consultation = $this->getAuthorizedConsultation($user, $consultationId);
        
        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found or unauthorized'], 404);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
            'type' => 'in:text,image,file',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Handle file attachment if present
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
            }

            // Create the message
            $message = Message::create([
                'consultation_id' => $consultation->id,
                'user_id' => $user->id,
                'content' => $request->message,
                'type' => $request->type ?? 'text',
                'attachment_path' => $attachmentPath,
                'is_read' => false,
            ]);

            // Update consultation last activity
            $consultation->touch();

            // Broadcast the message
            broadcast(new MessageSent($message, $user))->toOthers();

            DB::commit();

            return response()->json([
                'message' => $message->load('user'),
                'status' => 'sent'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Get conversation history with pagination
     */
    public function getMessages(Request $request, $consultationId): JsonResponse
    {
        $user = Auth::user();
        $consultation = $this->getAuthorizedConsultation($user, $consultationId);
        
        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found or unauthorized'], 404);
        }

        $page = $request->get('page', 1);
        $perPage = min($request->get('per_page', 20), 50);

        $messages = $consultation->messages()
            ->with(['user:id,name,role'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($messages);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, $consultationId): JsonResponse
    {
        $user = Auth::user();
        $consultation = $this->getAuthorizedConsultation($user, $consultationId);
        
        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found or unauthorized'], 404);
        }

        $messageIds = $request->get('message_ids', []);
        
        if (empty($messageIds)) {
            // Mark all unread messages as read
            $consultation->messages()
                ->where('user_id', '!=', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        } else {
            // Mark specific messages as read
            $consultation->messages()
                ->whereIn('id', $messageIds)
                ->where('user_id', '!=', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return response()->json(['status' => 'marked_as_read']);
    }

    /**
     * Get typing indicator status
     */
    public function typing(Request $request, $consultationId): JsonResponse
    {
        $user = Auth::user();
        $consultation = $this->getAuthorizedConsultation($user, $consultationId);
        
        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found or unauthorized'], 404);
        }

        $isTyping = $request->boolean('typing', false);
        
        // Broadcast typing status to other participants
        broadcast(new \App\Events\UserTyping($user, $consultation, $isTyping))->toOthers();

        return response()->json(['status' => 'typing_status_updated']);
    }

    /**
     * Get user's consultations based on their role
     */
    private function getUserConsultations(User $user)
    {
        $query = Consultation::with(['patient.user', 'doctor.user'])
            ->withCount(['messages', 'unreadMessages'])
            ->orderBy('updated_at', 'desc');

        if ($user->hasRole('Patient')) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->hasRole('Doctor')) {
            $query->where('doctor_id', $user->doctor->id);
        } elseif ($user->hasRole(['Admin', 'ClinicManager'])) {
            // Admin can see all consultations
        } else {
            return collect();
        }

        return $query->get();
    }

    /**
     * Get consultation if user is authorized to access it
     */
    private function getAuthorizedConsultation(User $user, $consultationId): ?Consultation
    {
        $consultation = Consultation::find($consultationId);
        
        if (!$consultation) {
            return null;
        }

        // Check authorization
        if ($user->hasRole('Patient') && $user->patient?->id === $consultation->patient_id) {
            return $consultation;
        }
        
        if ($user->hasRole('Doctor') && $user->doctor?->id === $consultation->doctor_id) {
            return $consultation;
        }
        
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return $consultation;
        }

        return null;
    }

    /**
     * Mark consultation messages as read for the current user
     */
    private function markMessagesAsRead(Consultation $consultation, User $user): void
    {
        $consultation->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }
}