<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait HandlesGuestMode
{
    /**
     * Check if the current request is from a guest user
     */
    protected function isGuest(Request $request): bool
    {
        return $request->attributes->get('is_guest', true);
    }

    /**
     * Get guest status information for API responses
     */
    protected function getGuestInfo(Request $request): array
    {
        return [
            'is_guest' => $this->isGuest($request),
            'user_authenticated' => !$this->isGuest($request),
        ];
    }

    /**
     * Return a response indicating authentication is required
     */
    protected function requiresAuthentication(
        string $action = 'perform this action',
        array $alternatives = []
    ): JsonResponse {
        return response()->json([
            'error' => 'authentication_required',
            'message' => "Please login to {$action}",
            'login_required' => true,
            'alternatives' => $alternatives,
            'auth_endpoints' => [
                'login' => '/api/v1/auth/login',
                'register' => '/api/v1/auth/register',
            ]
        ], 401);
    }

    /**
     * Add guest-friendly information to successful responses
     */
    protected function addGuestContext(array $data, Request $request): array
    {
        return array_merge($data, $this->getGuestInfo($request));
    }

    /**
     * Get list of actions that require authentication
     */
    protected function getAuthRequiredActions(): array
    {
        return [
            'favorites' => 'add items to favorites',
            'messaging' => 'send messages to doctors',
            'booking' => 'book appointments',
            'video_call' => 'start video consultations',
            'voice_call' => 'start voice consultations',
            'treatment_requests' => 'submit treatment requests',
            'treatment_plans' => 'accept or reject treatment plans',
            'reviews' => 'write reviews',
            'profile' => 'access your profile',
            'consultation_history' => 'view your consultation history',
        ];
    }

    /**
     * Get list of actions available to guests
     */
    protected function getGuestAllowedActions(): array
    {
        return [
            'browsing' => 'browse doctors and clinics',
            'searching' => 'search for healthcare providers',
            'viewing_profiles' => 'view doctor and clinic profiles',
            'reading_reviews' => 'read reviews and ratings',
            'viewing_gallery' => 'view before/after galleries',
            'home_feed' => 'browse featured content',
        ];
    }

    /**
     * Create a response with authentication requirements
     */
    protected function createAuthRequirementsResponse(Request $request): JsonResponse
    {
        return response()->json([
            'is_guest' => $this->isGuest($request),
            'auth_required_for' => $this->getAuthRequiredActions(),
            'guest_allowed_for' => $this->getGuestAllowedActions(),
            'message' => $this->isGuest($request) 
                ? 'You are browsing as a guest. Login to access more features.'
                : 'You are logged in and have access to all features.',
        ]);
    }

    /**
     * Check if user can perform action, return error response if not
     */
    protected function ensureCanPerformAction(
        Request $request,
        string $action,
        string $actionDescription = null
    ): ?JsonResponse {
        if ($this->isGuest($request)) {
            return $this->requiresAuthentication(
                $actionDescription ?? $action,
                $this->getGuestAllowedActions()
            );
        }

        return null; // User is authenticated, can proceed
    }
}