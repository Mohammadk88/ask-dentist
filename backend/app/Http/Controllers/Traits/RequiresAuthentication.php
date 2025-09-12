<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

trait RequiresAuthentication
{
    /**
     * Check if user is authenticated and abort with standardized response if not
     */
    protected function requireAuth(string $action = null): void
    {
        if (auth()->guest()) {
            abort(response()->json([
                'error_code' => 'auth_required',
                'message' => 'Login required.',
                'action' => $action ?: 'authenticate',
                'details' => 'This action requires user authentication. Please log in to continue.'
            ], 401));
        }
    }

    /**
     * Check if user has required role and abort with standardized response if not
     */
    protected function requireRole(string $role, string $action = null): void
    {
        $this->requireAuth($action);
        
        $user = auth()->user();
        
        if (!$user->hasRole($role)) {
            abort(response()->json([
                'error_code' => 'insufficient_permissions',
                'message' => "This action requires {$role} role.",
                'action' => $action ?: 'role_required',
                'required_role' => $role,
                'user_roles' => $user->getRoleNames()
            ], 403));
        }
    }

    /**
     * Authorize using Gate and abort with standardized response if denied
     */
    protected function authorizeAction(string $ability, mixed $arguments = null, string $action = null): void
    {
        $this->requireAuth($action);
        
        try {
            Gate::authorize($ability, $arguments);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(response()->json([
                'error_code' => 'authorization_failed',
                'message' => $e->getMessage(),
                'action' => $action ?: $ability,
                'ability' => $ability
            ], 403));
        }
    }

    /**
     * Return standardized auth required response
     */
    protected function authRequiredResponse(string $action = null): JsonResponse
    {
        return response()->json([
            'error_code' => 'auth_required',
            'message' => 'Login required.',
            'action' => $action ?: 'authenticate',
            'details' => 'This action requires user authentication. Please log in to continue.'
        ], 401);
    }
}