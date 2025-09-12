<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FCMController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Add FCM token for authenticated user
     */
    public function addToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $token = $request->input('token');

        $result = $this->fcmService->addTokenToUser($user, $token);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'FCM token added successfully',
            ]);
        }

        return response()->json([
            'status' => 'info',
            'message' => 'FCM token already exists for this user',
        ]);
    }

    /**
     * Remove FCM token for authenticated user
     */
    public function removeToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $token = $request->input('token');

        $result = $this->fcmService->removeTokenFromUser($user, $token);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'FCM token removed successfully',
            ]);
        }

        return response()->json([
            'status' => 'info',
            'message' => 'FCM token not found for this user',
        ]);
    }

    /**
     * Get user's FCM tokens
     */
    public function getTokens(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'tokens' => $user->fcm_tokens ?? [],
                'count' => count($user->fcm_tokens ?? [])
            ]
        ]);
    }

    /**
     * Subscribe to topic
     */
    public function subscribeToTopic(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $topic = $request->input('topic');

        $result = $this->fcmService->subscribeToTopic($user, $topic);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => "Successfully subscribed to topic: {$topic}",
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to subscribe to topic',
        ], 500);
    }

    /**
     * Unsubscribe from topic
     */
    public function unsubscribeFromTopic(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $topic = $request->input('topic');

        $result = $this->fcmService->unsubscribeFromTopic($user, $topic);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => "Successfully unsubscribed from topic: {$topic}",
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to unsubscribe from topic',
        ], 500);
    }
}
